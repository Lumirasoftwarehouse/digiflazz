<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramSosialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_sosials', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('status', ['0', '1'])->default('0');
            $table->foreignId('id_owner')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('program_sosials');
    }
}
