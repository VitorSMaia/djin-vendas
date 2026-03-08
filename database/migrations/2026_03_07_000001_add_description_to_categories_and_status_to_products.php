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
        Schema::table('categories', function (Blueprint $table): void {
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')
                    ->nullable()
                    ->after('name');
            }
        });

        Schema::table('products', function (Blueprint $table): void {
            if (!Schema::hasColumn('products', 'status')) {
                $table->string('status', 20)
                    ->default('active')
                    ->after('stock')
                    ->index();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            if (Schema::hasColumn('products', 'status')) {
                $table->dropIndex(['status']);
                $table->dropColumn('status');
            }
        });

        Schema::table('categories', function (Blueprint $table): void {
            if (Schema::hasColumn('categories', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};

