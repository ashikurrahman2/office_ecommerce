<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_ships', function (Blueprint $table) {
            $table->id();
            $table->string('product_link');
            $table->string('product_title');
            $table->string('category_name');
            $table->text('description');
            $table->json('images')->nullable();
            $table->double('price', 10, 2);
            $table->integer('quantity');
            $table->date('ship_to');
            $table->date('valid_to');
            $table->double('weight', 10, 2);
            $table->string('shipping_type');
            $table->double('length', 10, 2)->nullable();
            $table->double('width', 10, 2)->nullable();
            $table->double('height', 10, 2)->nullable();
            $table->enum('status', ['pending', 'cancelled', 'delivered'])->default('pending');
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
        Schema::dropIfExists('request_ships');
    }
};
