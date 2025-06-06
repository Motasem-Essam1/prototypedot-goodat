<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('users', 'status'))
        {

        }
        else{
            Schema::table('users', function (Blueprint $table){
                $table->enum('status', [1, 0])->default(1);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('users', 'status'))
        {
            Schema::table('users', function (Blueprint $table)
            {
                $table->dropColumn('status');
            });
        }
    }
}
