<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariablesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('variable_translations');
        Schema::dropIfExists('variables');

        Schema::create('variables', function (Blueprint $table) {
            $table->id();

            $table->string('key');
            $table->string('type');

            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->boolean('translatable')->default(0);

            $table->string('group')->nullable();
            $table->integer('in_group_position')->default(1);

            $table->text('value')->nullable();
            $table->boolean('status')->default(1);

            $table->timestamps();
        });

        Schema::create('variable_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');
            $table->unsignedBigInteger('variable_id');

            $table->text('content')->nullable();

            $table->unique(['variable_id', 'locale']);

            $table->foreign('variable_id')->references('id')->on('variables')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variable_translations');
        Schema::dropIfExists('variables');
    }
}
