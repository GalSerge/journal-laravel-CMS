<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editors', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('editor_id');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->default('1');
            $table->unsignedBigInteger('lang_id')->default('1');
            $table->boolean('active')->default('0');
            $table->boolean('is_main')->default('0');
            $table->string('surname');
            $table->string('initials');
            $table->string('path_img');
            $table->string('academic_degree');
            $table->string('country_city');
            $table->string('post');
            $table->string('university');
            $table->text('scientific_interests');
            $table->text('scientific_spec');
            $table->string('orcid_code');
            $table->text('reseacher_index_short');
            $table->text('reseacher_index_full');
            $table->text('important_publics');
            $table->string('contacts');
            $table->text('grant_activities');
            $table->text('expert_activities');
            $table->text('more_information');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('editors');
    }
}
