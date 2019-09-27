<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(config('access.table_names.password_histories'), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('password');
            $table->timestamps();
        });

        Schema::table(config('access.table_names.password_histories'), function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on(config('access.table_names.users'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('access.table_names.password_histories'));
    }
}
