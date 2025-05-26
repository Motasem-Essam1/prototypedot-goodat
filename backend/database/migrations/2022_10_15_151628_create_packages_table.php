<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_name', 100)->nullable()->default('Deft@');
            $table->integer('number_of_services')->unsigned()->nullable()->default(0);
            $table->integer('number_of_images_per_service')->unsigned()->nullable()->default(0);
            $table->enum('search_package_priority', ['High', 'Normal'])->nullable()->default('Normal');
            $table->boolean('tasks_notification_criteria')->nullable()->default(false);
            $table->boolean('has_price')->nullable()->default(false);
            $table->boolean('has_condition')->nullable()->default(false);
            $table->boolean('is_public')->default(false);
            $table->boolean('per_month')->nullable()->default(false);
            $table->double('price')->nullable()->default(0);
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('color')->nullable();
            $table->boolean('phone_status')->default(true);
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
        Schema::dropIfExists('packages');
    }
}
