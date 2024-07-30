<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histori_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nominal');
            $table->enum('jenis', [
                '0', // keluar
                '1' // masuk
            ]);
            $table->foreignId('userId')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('histori_transaksis');
    }
}
