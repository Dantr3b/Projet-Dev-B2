<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceTables extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 50);
            $table->string('email', 100)->unique();
            $table->string('password_hash', 255);
            $table->string('updated_at')->default(now());
            $table->dateTime('created_at')->default(now());
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->integer('stock_quantity');
            $table->string('name', 100);
            $table->text('description');
            $table->decimal('price', 10, 2);
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('category_name', 100);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->dateTime('created_at')->default(now());
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->dateTime('order_date');
            $table->string('status', 50);
            $table->decimal('total_amount', 10, 2);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->dateTime('payment_date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 50);
        });

        Schema::create('shipping', function (Blueprint $table) {
            $table->id('shipping_id');
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->string('shipping_address', 255);
            $table->dateTime('shipping_date')->nullable();
            $table->string('tracking_number', 100)->nullable();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->integer('rating');
            $table->text('comment')->nullable();
        });

        Schema::create('wishlists', function (Blueprint $table) {
            $table->id('wishlist_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->nullable();
            $table->string('name', 100)->nullable();
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id('wishlist_item_id');
            $table->foreignId('wishlist_id')->constrained('wishlists', 'wishlist_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->integer('quantity');
            $table->dateTime('added_at')->default(now());
        });

        Schema::create('shopping_cart', function (Blueprint $table) {
            $table->id('cart_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->dateTime('created_at')->default(now());
            $table->dateTime('updated_at')->nullable();
            $table->integer('total_items');
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->foreignId('cart_id')->constrained('shopping_cart', 'cart_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->integer('quantity');
            $table->dateTime('added_at')->default(now());
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('shopping_cart');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('shipping');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('users');
    }
}
