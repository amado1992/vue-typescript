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
            $table->increments('id');
            $table->string('codigo');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->boolean('activo')->default(true);
            $table->boolean('admin')->default(true);
            $table->boolean('primary')->default(false);
            $table->boolean('first_login')->default(false);
            $table->integer('persona_id')->unsigned()->nullable();
            $table->integer('instalacion_id')->unsigned();
            $table->string('password');
            $table->timestamp('last_login')->nullable();
            $table->longText('token')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('persona_id')->on('event_persona')->references('id');
            $table->foreign('instalacion_id')->on('mtinstalacion')->references('id');
        });
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
