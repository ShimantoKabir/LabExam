<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->integer('pin')->nullable();
            $table->string('name')->nullable();
            $table->string('token')->nullable();
            $table->string('email');
            $table->string('password')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->string('avatar')->nullable();
            $table->string('user_name')->nullable();
            $table->integer('user_role')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_infos');
    }
}
