<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class  CreateUsersTable extends Migration
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
            $table->foreignId('role_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreignId('teacher_id')
                ->nullable()
                ->references('id')->on('users')
                ->cascadeOnUpdate()->cascadeOnDelete();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('remember_token')->nullable();

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
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('users');
    }
}
