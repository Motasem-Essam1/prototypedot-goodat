<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveToCustomerReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_reviews', function (Blueprint $table) {
            $table->boolean('approvel')->nullable()->default(0);
            $table->integer('review_id');
            $table->string('review_type', 191);//create Type for service, provider, task
        });

        /*
        //Error: General error: 1553 Cannot drop index 'customer_reviews_user_id_foreign': needed in a foreign key constraint (SQL: alter table `customer_reviews` drop `user_id`)
        
        if (Schema::hasColumn('customer_reviews', 'user_id')){
            Schema::table('customer_reviews', function (Blueprint $table) {
               $table->dropColumn('user_id');
           });
        }
        */

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_reviews', function (Blueprint $table) {
            //
        });
    }
}
