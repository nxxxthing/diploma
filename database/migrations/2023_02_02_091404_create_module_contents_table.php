<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->boolean('status')->default(false);
            $table->integer('position')->default(0);
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->timestamps();
        });

        Schema::create('module_content_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_content_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('locale')->index();

            $table->string('title')->nullable();
            $table->text('text')->nullable();

            $table->unique(['module_content_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('module_content_translations');
        Schema::dropIfExists('module_contents');
    }
}
