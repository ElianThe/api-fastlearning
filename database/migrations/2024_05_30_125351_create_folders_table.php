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
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->mediumText('content')->nullable();
            $table->boolean('is_public')->default(0)->comment('0=hidden,1=visible');
            $table->bigInteger('parent_id')->nullable()->unsigned();
            $table->foreign("parent_id")->references("id")->on('folders')->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('created_by_user');
            $table->foreign('created_by_user')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folders');
    }
};
