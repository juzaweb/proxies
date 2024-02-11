<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table(
            'jwpr_proxies',
            function (Blueprint $table) {
                $table->string('username')->nullable();
                $table->string('password')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table(
            'jwpr_proxies',
            function (Blueprint $table) {
                $table->dropColumn(['username', 'password']);
            }
        );
    }
};
