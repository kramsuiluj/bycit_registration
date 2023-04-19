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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration')->constrained('registrations', 'id');
            $table->boolean('first_snack_am')->default(false);
            $table->boolean('first_snack_pm')->default(false);
            $table->boolean('second_snack_am')->default(false);
            $table->boolean('second_snack_pm')->default(false);
            $table->boolean('first_lunch')->default(false);
            $table->boolean('second_lunch')->default(false);
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
        Schema::dropIfExists('food');
    }
};
