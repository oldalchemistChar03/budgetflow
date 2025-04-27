<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('recurring')->default('none')->after('notes'); // 'none', 'daily', 'weekly', 'monthly'
            $table->date('next_due_date')->nullable()->after('recurring');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('recurring');
            $table->dropColumn('next_due_date');
        });
    }
};
