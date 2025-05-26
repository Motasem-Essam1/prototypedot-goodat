<?php

namespace App\Services;

use App\Models\Package;
use App\Utils\FileUtil;
use App\Utils\SmsUtil;
use App\Models\User;
use App\Models\UserData;
use App\Models\UserSubCategories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserService
{
    private $smsUtil;
    private $fileUtil;
    public function __construct(SmsUtil $smsUtil, FileUtil $fileUtil,
                                private readonly SubCategoryService $subCategoryService,
                                private  readonly UserSubCategoriesService $userSubCategoriesService)
    {
        $this->smsUtil = $smsUtil;
        $this->fileUtil =$fileUtil;
    }

    public function createUser(array $user):array{
        $data = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'phone_number' => $user['phone_number'],
            'country_code' => $user['phone_code'],
            'password' => Hash::make($user['password']),
            'phoned_Signed' => $user['phoned_Signed'],
        ]);
        $verification_code = $this->generateVerificationCode();
        $user_data = new UserData();
        $user_data->user_id = $data->id;
        $user_data->generated_Code = $this->generateUserInviteCode();
        $user_data->verify_code = $verification_code;
        $user_data->avatar = '/assets/img/ninja.svg';
        $user_data->user_type = $user['is_provider'] ? "Service Provider" : "Normal";
        $user_data->package_id = $user['is_provider'] ? Package::first()->id : null;
        $user_data->provider_type = $user['provider'];
        $user_data->nominated_by =isset($user['invitation_code']) ?  $this->validateNominatedBy($user['invitation_code']):null;
        $user_data->save();
        $sms_status = $this->validateIfProvider($data);
        return [
            "data" => $data,
            "sms_status" => $sms_status
        ];
    }

    /**
     * @param int $id
     * @return Builder|Model|null
     */
    public function getUser(int $id): Builder|Model|null
    {
       return User::query()->where('id', $id)->where('id', $id)->with('user_data')->first();
    }

    public function uploadUserData(array $data)
    {
        $name = $data['id'] . "-" . time();
        $path = "user-profile";
        $user = User::find($data['id']);
        $user->name = $data['full_name'];
        $user->email = $data['email'];
        $user_data = UserData::where('user_id', $data['id'])->first();
        if (isset($data['avatar'])){
            $image_path = $this->fileUtil->uploadFile($data['avatar'], $name, $path);
            $user_data->avatar = $image_path;
        }

        $user->phone_number = $data['phone_number'];
        $user->country_code = $data['country_code'];
        $user->save();
        $user_data->save();

        //Upload User Sub Categories
        UserSubCategories::where('user_id', $data['id'])->delete();

        if(!empty($data['user_sub_categories']))
        {
            foreach($data['user_sub_categories'] as $item){
                UserSubCategories::updateOrInsert(
                    [
                        'user_category_id' => $item,
                        'user_id' => $data['id']
                    ],
                );

            }
        }

        return $user;
    }

    public function updatePassword(array $data): array
    {
        $response = [];
        $user = User::find($data['id']);
        if(Hash::check($data['old_password'], $user->password)){
            $user->password = Hash::make($data['password']);
            $user->save();
            $response['status'] = true;
            $response['massage'] = "Password updated";
            return $response;
        }
        $response['status'] = false;
        $response['massage'] = "Password Invalid";
        return $response;
    }
    public function generateUserInviteCode():string{
        $id = User::latest('id')->first()->id;
        return getenv("APP_NAME") . ($id + 100);
    }

    public function generateVerificationCode():string{
        return rand(pow(10, 4), pow(10, 5) - 1);
    }

    /**
     * @param string $code
     * @return null|int
     */
    private function validateNominatedBy(string $code): ?int
    {
        $user = UserData::where('generated_Code', $code)->first();
        if (isset($user)){
            $user->number_of_invites += 1;
            $user->save();
            return $user->user_id;
        }
        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    private function validateIfProvider(User $user):bool{
        if ($user->user_data->user_type == "Service Provider"){
            $phone_number = $user->country_code . $user->phone_number;
            $code = $user->user_data->verify_code;
            return $this->smsUtil->sendVerificationCode($phone_number, $code);
        }
        return false;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getUserIdsArrayByCategoryId(int $id): array
    {
        $provider_id_array = [];

        $sub_categories = $this->subCategoryService->getSubCategoryByCategoryIdWithUser($id)->toArray();
        foreach ($sub_categories as $sub_category) {

            foreach($sub_category['user_sub_categories'] as $user_sub_categories)
            {
                //check if user is empty or null
                if(!empty($user_sub_categories['user_id']))
                {
                    //check if a value exists in an array
                    if(!(in_array($user_sub_categories['user_id'], $provider_id_array))){
                        array_push($provider_id_array, $user_sub_categories['user_id']);
                    }
                }
            }

        }
        return $provider_id_array;
    }

    public function getUserWithRateSubCategoriesByIdArray(array $provider_id_Array): Builder|array|Model|null|Collection
    {
        $users = User::with('user_data')->where('status', 1)->find($provider_id_Array);

        //start get UserSubCategories
        foreach($users as $user)
        {
            $user_sub_categories_text = $this->userSubCategoriesService->getUserSubCategoriesText($user['id']);
            $user['user_sub_categories_text'] = $user_sub_categories_text;

            //get rate
            if($user->CustomerReview()->where('review_type', 'provider_average')->get()->first())
            {
                $user['rate'] = $user->CustomerReview()->where('review_type', 'provider_average')->get()->first()->rate;
            }
            else{
                $user['rate'] = 0;
            }

            //get  number of customer review
            if($user->CustomerReview()->where('review_type', 'provider')->get())
            {
                $user['customer_review_number'] = $user->CustomerReview()->where('review_type', 'provider')->get()->count();
            }
            else{
                $user['customer_review_number'] = 0;
            }
        }

        return $users;
        //end get UserSubCategories
    }

}
