<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tmp_postcode_data', function (Blueprint $table) {
            $table->string('pcd', 10);
            $table->string('pcd2', 10);
            $table->string('pcds', 10);
            $table->integer('dointr');
            $table->integer('doterm');
            $table->string('oscty', 15);
            $table->string('ced', 15);
            $table->string('oslaua', 15);
            $table->string('osward', 15);
            $table->string('parish', 15);
            $table->tinyInteger('usertype');
            $table->integer('oseast1m');
            $table->integer('osnrth1m');
            $table->tinyInteger('osgrdind');
            $table->string('oshlthau', 15);
            $table->string('nhser', 15);
            $table->string('ctry', 15);
            $table->string('rgn', 15);
            $table->tinyInteger('streg');
            $table->string('pcon', 15);
            $table->string('eer', 15);
            $table->string('teclec', 15);
            $table->string('ttwa', 15);
            $table->string('pct', 15);
            $table->string('itl', 15);
            $table->string('statsward', 10);
            $table->string('oa01', 15);
            $table->string('casward', 10);
            $table->string('park', 15);
            $table->string('lsoa01', 15);
            $table->string('msoa01', 15);
            $table->tinyInteger('ur01ind');
            $table->string('oac01');
            $table->string('oa11', 15);
            $table->string('lsoa11', 15);
            $table->string('msoa11', 15);
            $table->string('wz11', 15);
            $table->string('ccg', 15);
            $table->string('bua11', 15);
            $table->string('buasd11', 15);
            $table->tinyInteger('ru11ind');
            $table->string('oac11', 10);
            $table->decimal('lat', 11, 8);
            $table->decimal('long', 11, 8);
            $table->string('lep1', 15);
            $table->string('lep2', 15);
            $table->string('pfa', 15);
            $table->integer('imd');
            $table->string('calncv', 15);
            $table->string('stp', 15);
            $table->string('oa21', 15);
            $table->string('lsoa21', 15);
            $table->string('msoa21', 15);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmp_postcode_data');
    }
};
