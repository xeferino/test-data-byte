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
            $table->id();
            $table->string('name',20);
            $table->string('surname', 20)->nullable($value=true);
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable($value=true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('description')->nullable($value=true);
            $table->string('img', 255)->nullable($value=true);
            $table->enum('role', ['admin', 'user'])->nullable();
            $table->boolean('info')->default(0)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                    'name'              => 'admin',
                    'surname'           => 'admin',
                    'email'             => 'admin@example.com',
                    'phone'             => '04149585692',
                    'email_verified_at' => '2020-07-23 11:41:34',
                    'password'          => bcrypt('admin'),
                    'description'       => 'administer System',
                    'img'               => 'default.png',
                    'role'              => 'admin'
            )
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
