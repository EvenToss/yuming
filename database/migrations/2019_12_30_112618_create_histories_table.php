<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('time');
            $table->unsignedInteger('pc_weight')->comment('PC权重');
            $table->unsignedInteger('m_weight')->comment('移动权重');
            $table->unsignedBigInteger('pc_vocabulary')->comment('PC词量');
            $table->unsignedBigInteger('m_vocabulary')->comment('移动词量');
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
        Schema::dropIfExists('histories');
    }
}
