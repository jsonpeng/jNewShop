<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateOrdersTable1 extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //订单表
        Schema::table('orders', function (Blueprint $table) {
           
            $table->float('dis_price')->nullable()->default(0)->comment('分佣金额/店主优惠金额');

         
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
      
    }
}
