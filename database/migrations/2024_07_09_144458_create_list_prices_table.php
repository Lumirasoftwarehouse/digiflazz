<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_prices', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->string('brand');
            $table->string('type');
            $table->string('seller_name');
            $table->integer('price');
            $table->string('buyer_sku_code');
            $table->string('seller_product_status');
            $table->string('unlimited_stock');
            $table->integer('stock');
            $table->string('multi');
            $table->string('start_cut_off');
            $table->string('end_cut_off');
            $table->text('desc');
            $table->integer('margin')->nullable();
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
        Schema::dropIfExists('list_prices');
    }
}
