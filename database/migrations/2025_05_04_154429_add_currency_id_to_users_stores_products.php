<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['currency_id']);
            $table->dropColumn('currency_id');
        });
    }
};

