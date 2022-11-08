<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id('lang_id');
            $table->string('code', 4);
            $table->string('title');
        });

        DB::table('languages')->insert(
            [
                [
                    'lang_id' => 1,
                    'code' => 'ru',
                    'title' => 'Русский'
                ],
                [
                    'lang_id' => 2,
                    'code' => 'en',
                    'title' => 'English'
                ]
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
        Schema::dropIfExists('languages');
    }
}
