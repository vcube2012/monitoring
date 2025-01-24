<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('owner_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Project::class)->index()->constrained();
            $table->unsignedBigInteger('user_id');
            $table->string('wallet');
            $table->text('secret_key');
            $table->timestamps();
            $table->unique(['user_id', 'wallet']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_wallets');
    }
};
