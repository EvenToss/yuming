<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJuzisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juzis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->comment('历史年份');
            $table->string('title')->comment('历史标题');
            $table->unsignedBigInteger('domain_id')->index()->comment('域名id');
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
        Schema::dropIfExists('juzis');
    }
}
