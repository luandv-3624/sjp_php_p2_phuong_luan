<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAmenitiesUniqueIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->unique(['venue_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropUnique(['venue_id', 'code']);
            $table->unique('code');
        });
    }
}
