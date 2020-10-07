<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('konten', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('halaman_id')->nullable();
            $table->string('nama');
            $table->longText('konten')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('halaman_id')->references('id')->on('halaman');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konten');
    }
}
