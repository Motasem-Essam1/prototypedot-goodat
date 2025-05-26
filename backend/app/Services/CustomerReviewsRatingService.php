<?php

namespace App\Services;

class CustomerReviewsRatingService
{
    private array $customer_reviews_rating = [];
    private array $auth_rating = [];

    public function __construct()
    {

    }

    public function setRatingZero(): void
    {
        $this->customer_reviews_rating['quality'] = 0;
        $this->customer_reviews_rating['time'] = 0;
        $this->customer_reviews_rating['accuracy'] = 0;
        $this->customer_reviews_rating['communication'] = 0;
        $this->customer_reviews_rating['average'] = 0;

        $this->customer_reviews_rating['quality_percentage'] = 0;
        $this->customer_reviews_rating['time_percentage'] = 0;
        $this->customer_reviews_rating['accuracy_percentage'] = 0;
        $this->customer_reviews_rating['communication_percentage'] = 0;
        $this->customer_reviews_rating['average_percentage'] = 0;

        $this->auth_rating['quality'] = 0;
        $this->auth_rating['time'] = 0;
        $this->auth_rating['accuracy'] = 0;
        $this->auth_rating['communication'] = 0;
        $this->auth_rating['average'] = 0;
        $this->auth_rating['comment'] = "";
    }


    public function CalculateRating($customerReview): void
    {
        if(!empty($customerReview))
        {
            $this->customer_reviews_rating['quality'] = +$customerReview['quality'];
            $this->customer_reviews_rating['time'] = +$customerReview['time'];
            $this->customer_reviews_rating['accuracy'] = +$customerReview['accuracy'];
            $this->customer_reviews_rating['communication'] = +$customerReview['communication'];
            $this->customer_reviews_rating['average'] = +$customerReview['rate'];

            $this->customer_reviews_rating['quality_percentage'] = +sprintf("%0.2f", ($customerReview['quality']/5) * 100);
            $this->customer_reviews_rating['time_percentage']  = +sprintf("%0.2f", ($customerReview['time']/5) * 100);
            $this->customer_reviews_rating['accuracy_percentage'] = +sprintf("%0.2f", ($customerReview['accuracy']/5) * 100);
            $this->customer_reviews_rating['communication_percentage'] = +sprintf("%0.2f", ($customerReview['communication']/5) * 100);
            $this->customer_reviews_rating['average_percentage'] = +sprintf("%0.2f", ($customerReview['rate']/5) * 100);
        }

    }


    public function CalculateAuthRating($customerReview): void
    {
        if(!empty($customerReview))
        {
             $this->auth_rating['quality'] = +$customerReview['quality'];
             $this->auth_rating['time'] = +$customerReview['time'];
             $this->auth_rating['accuracy'] = +$customerReview['accuracy'];
             $this->auth_rating['communication'] = +$customerReview['communication'];
             $this->auth_rating['average'] = +$customerReview['rate'];
             $this->auth_rating['comment'] = $customerReview['description'];
        }

    }

     /**
     * get customer reviews rating
     *
     * @return array
     */
    public function getCustomerReviewsRating(): array
    {
        return $this->customer_reviews_rating;
    }

    /**
     * get customer reviews Auth Rating
     *
     * @return array
     */
    public function getAuthRating(): array
    {
        return $this->auth_rating;
    }
}
