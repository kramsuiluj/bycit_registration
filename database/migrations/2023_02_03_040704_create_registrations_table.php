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
     *
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools', 'id');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middle_initial')->nullable();
            $table->enum('type', ['Student', 'Teacher'])->default('Student');
            $table->string('nickname')->nullable();
            $table->enum('firstDay', ['yes', 'no'])->default('no');
            $table->enum('secondDay', ['yes', 'no'])->default('no');
            $table->enum('paid', ['yes', 'no'])->default('no');
            $table->foreignID('tshirt')->constrained('sizes', 'id')->nullable(false);
            $table->datetime('date_registered');
            $table->softDeletes();
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
        Schema::dropIfExists('registrations');
    }
};
