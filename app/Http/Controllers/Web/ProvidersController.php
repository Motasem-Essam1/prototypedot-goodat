<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderResource;
use App\Models\Configuration;
use App\Models\ProviderContact;

use App\Services\ConfigurationService;
use App\Services\CustomerReviewsRatingService;
use App\Services\CustomerReviewsService;
use App\Services\ProviderContactService;
use App\Services\UserService;
use App\Services\UserSubCategoriesService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProvidersController extends Controller
{
    public function __construct(private readonly CustomerReviewsRatingService $customerReviewsRatingService,
                                private readonly UserService $userService,
                                private readonly UserSubCategoriesService $userSubCategoriesService,
                                private readonly CustomerReviewsService $customerReviewsService,
                                private readonly ConfigurationService $configurationService ,
                                private readonly ProviderContactService $providerContactService)
    {

    }


    public function view(string $id): Application|Factory|View|Model|array|null
    {
        //set average and percentage value equal zero
        $this->customerReviewsRatingService->setRatingZero();

        $provider = $this->userService->getUser($id);

        if (!isset($provider)){
            abort(404);
        }

        //start get rate of current user for this provider
         if (Auth::check()) {
             if ($provider['status'] == 0 && $provider['id'] != Auth::user()['id']) {
                 abort(404);
             }

             //get CustomerReview average for task by task id
             $auth_customer_reviews = $this->customerReviewsService->getCustomerReviewByUserId($id, 'provider', Auth::user()['id']);
             //Calculate rating of user  average, quality, time, accuracy, communication for task
             $this->customerReviewsRatingService->CalculateAuthRating($auth_customer_reviews);
         }
         else {
             if ($provider['status'] == 0) {
                 abort(404);
             }
         }
        //end get rate of current user for this provider

        //start get UserSubCategories
        $user_sub_categories_text = $this->userSubCategoriesService->getUserSubCategoriesText($id);
        //end get UserSubCategories

        //get average CustomerReview for this provider
        $customer_review = $this->customerReviewsService->getCustomerReview($id, 'provider_average');

        if(!$customer_review->isEmpty()){
            $this->customerReviewsRatingService->CalculateRating($customer_review[0]);
        }

        //get all CustomerReview for this provider
        $customer_reviews = $this->customerReviewsService->getCustomerReviewProvider($id);

        $configurations = $this->configurationService->showByKey("currency_symbol")["configuration"];

        $data = [
            'provider' => $provider,
            'user_id' => Auth::id(),
            'data' => $provider['user_data'],
            'service' => $provider['servicesActive'],
            'customer_reviews' => $customer_reviews,
            'customer_reviews_rating' => $this->customerReviewsRatingService->getCustomerReviewsRating(),
            'auth_rating' => $this->customerReviewsRatingService->getAuthRating(),
            'user_sub_categories_text' => $user_sub_categories_text,
            'configurations' => $configurations,
        ];
        return view('profile.index', $data);
    }


    public function providerUpdateRate(Request $request): JsonResponse
    {
        //update customer review of task
        $flag = $this->customerReviewsService->UpdateCustomerReview($request, 'provider');

        if($flag) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully updated your review'
            ]);
        }
        else{
            return response()->json('Failed to update review');
        }
    }

    public function getProviderByCategory($id): array
    {
        $provider_id_Array = $this->userService->getUserIdsArrayByCategoryId($id);

        $users = $this->userService->getUserWithRateSubCategoriesByIdArray($provider_id_Array);

        $users = ProviderResource::collection($users);
        $statisticCollection = collect($users);
        $users = $statisticCollection->sortByDesc('rate')->take(20);


        return $users->values()->all();
    }

    public function providerContact(Request $request): void
    {
        $this->providerContactService->createProviderContact($request);
    }
}
