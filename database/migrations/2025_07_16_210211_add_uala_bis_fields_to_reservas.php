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
    Schema::table('reservas', function (Blueprint $table) {
        $table->string('uala_bis_uuid')->nullable()->after('mp_init_point');
        $table->text('uala_bis_checkout_link')->nullable();
        $table->string('uala_bis_external_reference')->nullable();
        $table->longText('uala_bis_webhook_response')->nullable();
        
        $table->index('uala_bis_uuid');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            //
        });
    }
};
