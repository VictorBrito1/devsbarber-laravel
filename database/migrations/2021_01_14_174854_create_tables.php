<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->default('default.png');
            $table->string('email')->unique();
            $table->string('password');
        });

        Schema::create('barbers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('avatar')->default('default.png');
            $table->float('stars')->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
        });

        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('barber_id')->constrained('barbers');
        });

        Schema::create('user_appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('barber_id')->constrained('barbers');
            $table->datetime('appointment_at');
        });

        Schema::create('barber_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('barbers');
            $table->string('url');
        });

        Schema::create('barber_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('barbers');
            $table->float('rate');
        });

        Schema::create('barber_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('barbers');
            $table->string('name');
            $table->float('price');
        });

        Schema::create('barber_testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('barbers');
            $table->foreignId('user_id')->constrained('users');
            $table->float('rate');
            $table->string('body');
        });

        Schema::create('barber_availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('barbers');
            $table->unsignedInteger('weekday');
            $table->text('hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_favorites');
        Schema::dropIfExists('user_appointments');
        Schema::dropIfExists('barbers');
        Schema::dropIfExists('barber_photos');
        Schema::dropIfExists('barber_reviews');
        Schema::dropIfExists('barber_services');
        Schema::dropIfExists('barber_testimonials');
        Schema::dropIfExists('barber_availability');
    }
}
