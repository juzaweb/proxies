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
        Schema::create(
            'jwpr_proxies',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('ip', 150)->index();
                $table->string('port', 10)->index();
                $table->string('protocol', 20)->index();
                $table->string('country', 5)->nullable();
                $table->boolean('is_free')->default(true);
                $table->boolean('active')->default(true);
                $table->unique(['ip', 'port', 'protocol']);
                $table->timestamps();
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
        Schema::dropIfExists('jwpr_proxies');
    }
};
