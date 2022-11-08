<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id('id');
            $table->integer('section_id');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->default('1');
            $table->unsignedBigInteger('lang_id')->default('1');
            $table->boolean('active')->default('0');
            $table->boolean('show_in_menu')->default('1');
            $table->string('address', 50);
            $table->integer('order');
            $table->unsignedBigInteger('parent_id')->default('0');
            $table->boolean('is_module')->default('0');
            $table->string('title', 100);
            $table->string('description', 200);
            $table->longText('text');
        });

        DB::table('sections')->insert(
            [
                [
                    'section_id' => 1,
                    'lang_id' => 1,
                    'address' => 'archive',
                    'order' => 1,
                    'title' => 'Архив',
                    'is_module' => 1

                ],
                [
                    'section_id' => 2,
                    'lang_id' => 1,
                    'address' => 'editorial-board',
                    'order' => 2,
                    'title' => 'Редакционный совет',
                    'is_module' => 1
                ],
                [
                    'section_id' => 1,
                    'lang_id' => 2,
                    'address' => 'archive',
                    'order' => 1,
                    'title' => 'Archive',
                    'is_module' => 1

                ],
                [
                    'section_id' => 2,
                    'lang_id' => 2,
                    'address' => 'editorial-board',
                    'order' => 2,
                    'title' => 'Editorial board',
                    'is_module' => 1
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
        Schema::dropIfExists('sections');
    }
}
