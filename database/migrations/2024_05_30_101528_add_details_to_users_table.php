<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
            $table->after('name', function ($table) {
                $table->string('last_name');
                $table->string('username');
            });
            $table->after('password', function ($table) {
                $table->integer('role');
                $table->string('status');
                $table->json('settings')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('username');
            $table->dropColumn('role');
            $table->dropColumn('status');
            $table->dropColumn('settings');
        });
    }
};
