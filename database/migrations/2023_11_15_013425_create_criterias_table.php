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
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->string('no', 20)->unique();
            $table->string('criteria_code', 20)->unique();
            $table->string('criteria_name', 100);
            $table->string('criteria_type', 10);

            $table->decimal('weight',2);
            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null'); // <-- Sesuaikan onDelete

            // TAMBAHKAN updated_by (optional, tapi disarankan untuk audit)
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null'); // <-- TAMBAHKAN INI
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('criterias');
    }
};