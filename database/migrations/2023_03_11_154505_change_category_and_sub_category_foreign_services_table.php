<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCategoryAndSubCategoryForeignServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table){
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')
                ->references('id')
                ->on('sub_categories')
                ->nullOnDelete();
            $table->unsignedBigInteger('parent_category_id')->nullable()->change();
            $table->dropForeign(['parent_category_id']);
            $table->foreign('parent_category_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
