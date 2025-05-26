<?php

namespace App\Services;

use App\Models\CustomerReview;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laminas\Diactoros\Response\JsonResponse;


class CustomerReviewsService
{

    public function __construct()
    {

    }

    /**
     * get Customer Review
     *
     * @param int $id
     * @param String $type
     * @return Collection|null
     */
    public function getCustomerReview(int $id, String $type): Collection|null
    {
        return CustomerReview::query()->where('approvel', '1')->where('status', 1)->where('review_type', '=', $type)->where('review_id', '=', $id)->get();
    }

    /**
     * get Customer Review
     *
     * @param int $id
     * @return Collection|null
     */
    public function getCustomerReviewProvider(int $id): Collection|null
    {
        return CustomerReview::with('user.user_data')->with('customer.user_data')
            ->where('approvel', '1')->where('review_type', '=', "provider")->where('review_id', '=', $id)->get();
    }
    /**
     * get Customer Review by user id
     *
     * @param int $id
     * @param String $type
     * @param int $userId
     * @return CustomerReview|Model|null
     */
    public function getCustomerReviewByUserId(int $id, String $type, int $userId): CustomerReview|null|Model
    {
        return CustomerReview::query()->where('approvel', '1')->where('review_type', '=', $type)->where('review_id', '=', $id)
            ->where('customer_id', '=', $userId)->first();
    }


    /**
     * update customer review of task
     *
     * @param $request
     * @param String $type
     * @return JsonResponse|bool
     */
    public function UpdateCustomerReview($request,String $type): JsonResponse|bool
    {
        $total_rate = ($request->quality + $request->time + $request->accuracy + $request->communication)/4;


        if (Auth::check()) {
            $customerReview = CustomerReview::query()->Where('review_id', $request->review_id)->Where('customer_id', Auth::user()['id'])
            ->Where('review_type', $type)->first();

            if($customerReview)
            {
            //Update
            CustomerReview::query()->updateOrInsert(
                ['review_id' => $request->review_id, 'customer_id' => Auth::user()['id'], 'review_type' => $type],
                ['rate' => $total_rate, 'description' => $request->comment,
                'quality' =>  $request->quality, 'time' =>  $request->time,
                'accuracy' =>  $request->accuracy, 'communication' =>  $request->communication, 'approvel' => 0,
                'updated_at' =>  date('Y-m-d H:i:s')]);

            }
            else{
            //INSERT
            CustomerReview::query()->updateOrInsert(
                ['review_id' => $request->review_id, 'customer_id' => Auth::user()['id'], 'review_type' => $type],
                ['rate' => $total_rate, 'description' => $request->comment,
                'quality' =>  $request->quality, 'time' =>  $request->time,
                'accuracy' =>  $request->accuracy, 'communication' =>  $request->communication, 'approvel' => 0,
                'created_at' =>  date('Y-m-d H:i:s'),
                ]);
            }

            $this->UpdateCustomerReviewAverage($request->review_id, $type);

            return true;
        }
        return false;
    }


    public function UpdateCustomerReviewUserStatus(int $id,int $status): void
    {
        //update user customer review
        CustomerReview::query()->where('customer_id', $id)->update(['status' => $status]);

        $customer_reviews = CustomerReview::query()->where('customer_id', $id)->whereIn('review_type', ['provider', 'service', 'task'])->get();
        foreach($customer_reviews as $customer_review)
        {
            $this->UpdateCustomerReviewAverage($customer_review['review_id'], $customer_review['review_type']);
        }

        //update user review
        CustomerReview::query()->where('review_id', $id)->where('review_type', 'provider')->update(['status' => $status]);
        $this->UpdateCustomerReviewAverage($id, 'provider');


        //update user services and tasks review
        $user = User::where('id' , $id)->first();

        $services = $user['services'];
        foreach($services as $service)
        {
            $customer_reviews = CustomerReview::query()->where('review_id',  $service['id'])->where('review_type', 'service')->get();

            if($customer_reviews)
            {
                CustomerReview::query()->where('review_id', $service['id'])->where('review_type', 'service')->update(['status' => $status]);
                $this->UpdateCustomerReviewAverage($service['id'], 'service');
            }
        }

        $tasks = $user['tasks'];
        foreach($tasks as $task)
        {
            $customer_reviews = CustomerReview::query()->where('review_id',  $task['id'])->where('review_type', 'task')->get();

            if($customer_reviews)
            {
                CustomerReview::query()->where('review_id', $task['id'])->where('review_type', 'task')->update(['status' => $status]);
                $this->UpdateCustomerReviewAverage($task['id'], 'service');
            }
        }
    }

    public function UpdateCustomerReviewAverage(int $id,String $type)
    {
        //update avergae task to use in search bar
        $average = 0;
        $quality_average = 0;
        $time_average = 0;
        $accuracy_average = 0;
        $communication_average = 0;

        $customer_reviews = CustomerReview::where('review_type', '=', $type)->where('review_id', '=',$id)->where('approvel', '1')->where('status', 1)->get();

        if(sizeof($customer_reviews) > 0){
            /*calculate number of rate */
            foreach($customer_reviews as $customer_review)
            {
                $average = $average + $customer_review->rate;
                $quality_average = $quality_average + $customer_review->quality;
                $time_average = $time_average + $customer_review->time;
                $accuracy_average = $accuracy_average + $customer_review->accuracy;
                $communication_average = $communication_average + $customer_review->communication;
            }

            //calculate average
            $average = $average/sizeof($customer_reviews);
            $quality_average = $quality_average/sizeof($customer_reviews);
            $time_average = $time_average/sizeof($customer_reviews);
            $accuracy_average = $accuracy_average/sizeof($customer_reviews);
            $communication_average = $communication_average/sizeof($customer_reviews);
        }

        CustomerReview::updateOrInsert(
            ['review_id' => $id, 'review_type' => $type . '_average'],
            ['rate' => $average, 'description' => "",
                'quality' =>  $quality_average, 'time' =>  $time_average,
                'accuracy' =>  $accuracy_average, 'communication' =>  $communication_average, 'approvel' => 1],

        );
    }


    public function removeReviews($id, $type) {
        $data = CustomerReview::where('review_id', $id)->where('review_type', $type);
        $average = CustomerReview::where('review_id', $id)->where('review_type', $type.'_average');
        if ($data->count() > 0) {
            $data->delete();
        }

        if ($average->count() > 0) {
            $average->delete();
        }
    }
}
