<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained()->onDelete('cascade');
            $table->string('nama_file');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fotos');
    }
};
