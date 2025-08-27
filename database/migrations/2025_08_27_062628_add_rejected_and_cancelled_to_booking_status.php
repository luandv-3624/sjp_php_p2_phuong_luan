<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRejectedAndCancelledToBookingStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_status', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE bookings 
                MODIFY status ENUM(
                    'pending',
                    'confirmed-unpaid',
                    'paid-pending',
                    'partial-pending',
                    'accepted',
                    'done',
                    'rejected',
                    'cancelled'
                ) DEFAULT 'pending'
            ");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_status', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE bookings 
                MODIFY status ENUM(
                    'pending',
                    'confirmed-unpaid',
                    'paid-pending',
                    'partial-pending',
                    'accepted',
                    'done'
                ) DEFAULT 'pending'
            ");
        });
    }
}
