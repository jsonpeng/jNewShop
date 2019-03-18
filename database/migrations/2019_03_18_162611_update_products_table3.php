<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductsTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('products', 'fenyong_rate')) {
            Schema::table('products', function (Blueprint $table) {
                $table->float('fenyong_rate')->nullable()->default(0)->comment('分佣比率');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('products', 'fenyong_rate')) {
            Schema::table('products', function (Blueprint $table) {
                 $table->dropColumn('fenyong_rate');
            });
        }
    }
}
