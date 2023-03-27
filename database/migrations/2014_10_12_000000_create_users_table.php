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
        Schema::create('cabinets', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('logo')->nullable();
            $table->year('year');
            $table->string('description')->nullable();

            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('logo')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('cabinet_id')->constrained('cabinets')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('nim')->unique()->nullable();
            $table->string('na')->unique()->nullable();
            $table->string('nama_bagus')->nullable();
            $table->string('avatar')->nullable();
            
            $table->string('email')->unique();
            
            $table->string('name');
            $table->string('password');
            $table->year('year')->nullable();
            
            // nullable foreign keys to departments and cabinets
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('cascade');
            $table->foreignId('cabinet_id')->nullable()->constrained('cabinets')->onDelete('cascade');

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('programs', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('description')->nullable();
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('cabinets');
    }
};
