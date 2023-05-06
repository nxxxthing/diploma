<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('faculty_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->foreignId('faculty_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->string('title')->nullable();
            $table->string('short_title')->nullable();

            $table->unique(['locale', 'faculty_id']);
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
        Schema::dropIfExists('faculty_translations');
        Schema::dropIfExists('faculties');
    }
};
