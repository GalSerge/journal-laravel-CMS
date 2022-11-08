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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('sname');
            $table->string('fname');
            $table->string('email');
            $table->string('username')->unique();
            $table->string('password');
            $table->boolean('active')->default(0);
        });

        DB::table('users')->insert(
            [
                'username' => 'admin',
                'password' => md5('admin')
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
