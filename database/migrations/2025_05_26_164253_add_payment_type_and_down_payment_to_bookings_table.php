<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->enum('payment_plan', ['full', 'partial'])->default('full'); // full أو partial
        $table->decimal('down_payment', 10, 2)->nullable(); // قيمة العربون في حالة الدفع الجزئي
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['payment_plan', 'down_payment']);
    });
}

};
