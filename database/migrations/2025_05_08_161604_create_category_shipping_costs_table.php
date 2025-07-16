<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryShippingCostsTable extends Migration
{
    public function up()
    {
        Schema::create('category_shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->decimal('air_cost', 10, 2)->nullable();
            $table->string('air_delivery_time')->nullable(); // e.g., "3-5 days"
            $table->decimal('ship_cost', 10, 2)->nullable();
            $table->string('ship_delivery_time')->nullable(); // e.g., "15-30 days"
            $table->string('origin')->nullable(); // e.g., "China", "USA"
            $table->timestamps();

            // Foreign key constraint (optional)
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_shipping_costs');
    }


};
