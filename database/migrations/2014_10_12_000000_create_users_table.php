<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('access.table_names.users'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('password_changed_at')->nullable();
            $table->string('avatar_type')->default('gravatar');
            $table->string('avatar_location')->nullable();
            $table->string('confirmation_code')->nullable();
            $table->boolean('confirmed')->default(true);
            $table->string('timezone')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->boolean('to_be_logged_out')->default(false);
            $table->rememberToken();
            $table->boolean('enable')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('access.table_names.users'));
    }
}
