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
        Schema::table('sales', function (Blueprint $table): void {
            // Remove old columns if they exist
            $columnsToDrop = ['product_id', 'quantity', 'unit_price', 'total_price'];
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('sales', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (!Schema::hasColumn('sales', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();
            } else {
                $table->foreignId('user_id')->nullable()->change();
            }

            if (!Schema::hasColumn('sales', 'sale_date')) {
                $table->dateTime('sale_date')
                    ->after('user_id')
                    ->index();
            }

            if (!Schema::hasColumn('sales', 'total')) {
                $table->decimal('total', 10, 2)
                    ->after('sale_date')
                    ->default(0);
            }
        });

        if (!Schema::hasTable('sale_items')) {
            Schema::create('sale_items', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('sale_id')
                    ->constrained('sales')
                    ->cascadeOnDelete();
                $table->foreignId('product_id')
                    ->constrained()
                    ->cascadeOnDelete();
                $table->unsignedInteger('quantity');
                $table->decimal('unit_price', 8, 2);
                $table->decimal('total_price', 10, 2);
                $table->timestamps();

                $table->index(['sale_id', 'product_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_items');

        Schema::table('sales', function (Blueprint $table): void {
            if (Schema::hasColumn('sales', 'total')) {
                $table->dropColumn('total');
            }

            if (Schema::hasColumn('sales', 'sale_date')) {
                $table->dropColumn('sale_date');
            }

            if (Schema::hasColumn('sales', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            // Restore old columns
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);
        });
    }
};

