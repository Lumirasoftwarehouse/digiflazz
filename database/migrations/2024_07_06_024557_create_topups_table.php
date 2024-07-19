<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topups', function (Blueprint $table) {
            $table->id();
            $table->string('jumlah_transaksi');
            $table->string('keterangan');
            $table->string('account_number')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->string('note')->nullable();
            $table->string('balance')->nullable();
            $table->string('mutation_id')->nullable();
            $table->string('bank_id')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_type')->nullable();
            $table->string('bukti_transfer');
            $table->enum('status', [
                '0', // belum validasi
                '1' // sudah divalidasi
            ])->default('0');
            $table->foreignId('userId')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('rekeningId')->constrained('rekenings')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('topups');
    }
}
