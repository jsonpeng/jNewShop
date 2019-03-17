<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCodesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->comment('代码');
            $table->integer('use')->nullable()->default(0)->comment('使用状态 0 1');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('codes');
    }
}
