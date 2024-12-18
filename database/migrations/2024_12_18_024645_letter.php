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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->integer('year'); // Tahun surat
            $table->integer('number'); // Nomor urut surat
            $table->string('letter_number'); // Format nomor surat resmi
            $table->integer('attachments')->nullable(); // Lampiran surat (opsional)
            $table->string('title'); // Judul atau perihal surat
            $table->string('slug'); 
            $table->date('letter_date'); // Tanggal surat
            $table->string('recipient_name'); // Nama penerima
            $table->text('recipient_address'); // Alamat penerima
            $table->string('sender_name'); // Nama pengirim (organisasi)
            $table->text('content'); // Isi surat
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('letters');
    }
};
