<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id('issue_id');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->default('1');
            $table->integer('journal_number');
            $table->integer('alt_number');
            $table->string('year');
            $table->date('published');
            $table->string('doi_code');
            $table->longtext('annotation');
            $table->string('path_cover');
            $table->string('path_pdf');
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id('subject_id');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->default('1');
            $table->unsignedBigInteger('lang_id')->default('1');
            $table->string('title');
        });


        Schema::create('articles', function (Blueprint $table) {
            $table->id('id');
            $table->integer('article_id');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->default('1');
            $table->unsignedBigInteger('lang_id')->default('1');
            $table->unsignedBigInteger('issue_id');
            $table->boolean('is_published')->default('1');
            $table->boolean('revoked')->default('0');
            $table->date('retracted');
            $table->text('retracted_reason');
            $table->string('doi_code');
            $table->string('udk_code');
            $table->string('title');
            $table->string('pages');
            $table->unsignedBigInteger('subject_id')->default('1');
            $table->date('submitted');
            $table->date('approved');
            $table->date('accepted');
            $table->date('published');
            $table->text('annotation');
            $table->text('thanks');
            $table->text('authors_contribution');
            $table->text('quotation');
            $table->text('references');
            $table->text('keywords');
            $table->longText('text');
            $table->string('path_text');
            $table->string('path_pdf');
            $table->integer('downloads');
            $table->integer('views');
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id('id');
            $table->integer('author_id');
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->default('1');
            $table->unsignedBigInteger('lang_id')->default('1');
            $table->integer('article_id');
            $table->string('orcid_code');
            $table->string('surname');
            $table->string('initials');
            $table->string('academic_degree');
            $table->string('post');
            $table->string('university');
            $table->string('city');
            $table->string('email');
        });

//        Schema::create('keywords', function (Blueprint $table) {
//            $table->id('keyword_id');
//            $table->unsignedBigInteger('lang_id')->default('1');
//            $table->string('word');
//            $table->integer('article_id');
//
//        });


//        Schema::create('keywords_articles', function (Blueprint $table) {
//            $table->id('word_article_id');
//            $table->unsignedBigInteger('keyword_id');
//            $table->foreign('keyword_id')->references('keyword_id')->on('keywords')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//            $table->unsignedBigInteger('article_id');
//            $table->foreign('article_id')->references('article_id')->on('articles')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//        });

        DB::table('subjects')->insert(
            [
                [
                    'user_id' => 1,
                    'lang_id' => 1,
                    'title' => ''
                ],
                [
                    'user_id' => 1,
                    'lang_id' => 2,
                    'title' => ''
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
//        Schema::dropIfExists('keywords_articles');
//        Schema::dropIfExists('keywords');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('issues');
    }
}
