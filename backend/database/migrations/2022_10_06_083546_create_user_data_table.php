<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained("users");
            $table->string('verify_code', 10)->nullable();
            $table->enum('user_type', ['Normal', 'Service Provider'])->nullable()->default('Normal');
            $table->string('provider_id')->nullable()->default(1);
            $table->enum('provider_type', ['facebook', 'google', 'web', 'mobile', 'admin'])->nullable()->default('web');
            $table->text('avatar')->nullable();
            $table->string('generated_Code', 100)->nullable()->default('Deft@');
            $table->integer('number_of_invites')->unsigned()->nullable()->default(0);
            $table->integer('nominated_by')->unsigned()->nullable();
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
        Schema::dropIfExists('user_data');
    }
}
