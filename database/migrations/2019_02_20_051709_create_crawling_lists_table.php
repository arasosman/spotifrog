<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrawlingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawling_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable(true);
            $table->string('uniq_id');
            $table->string('type')->default('artist');
            $table->boolean('relative_scan')->default(false);
            $table->integer('status')->default(1);
            $table->string('result')->nullable(true);
            $table->string('attamp')->nullable(true);
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
        Schema::dropIfExists('crawling_lists');
    }
}
