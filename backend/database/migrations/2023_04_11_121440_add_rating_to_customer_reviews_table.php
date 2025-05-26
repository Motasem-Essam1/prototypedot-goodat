<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingToCustomerReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_reviews', function (Blueprint $table) {
            $table->decimal('quality', 3, 2);
            $table->decimal('time', 3, 2);
            $table->decimal('accuracy', 3, 2);
            $table->decimal('communication', 3, 2);
            $table->decimal('rate', 3, 2)->change();
            $table->dropForeign('customer_reviews_user_id_foreign');
            $table->dropColumn('user_id');
            $table->dropForeign('customer_reviews_category_id_foreign');
            $table->dropColumn('category_id');
            $table->foreignId('customer_id')->nullable()->change();
        });
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
