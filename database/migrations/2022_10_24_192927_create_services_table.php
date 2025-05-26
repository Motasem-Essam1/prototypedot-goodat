<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('category_id')->constrained('sub_categories');
            $table->foreignId('parent_category_id')->constrained('categories');
            $table->string('service_name', 100)->nullable();
            $table->string('service_slug', 100)->nullable();
            $table->text('service_description')->nullable();
            $table->double('starting_price')->nullable();
            $table->double('ending_price')->nullable();
            $table->string('location_lng')->nullable();
            $table->string('location_lat')->nullable();
            $table->boolean('is_active')->nullable()->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
