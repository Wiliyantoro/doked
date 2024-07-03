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
        Schema::table('regulations', function (Blueprint $table) {
            $table->string('regulation_number')->after('type')->nullable();
        });
    }

    public function down()
    {
        Schema::table('regulations', function (Blueprint $table) {
            $table->dropColumn('regulation_number');
        });
    }
};
