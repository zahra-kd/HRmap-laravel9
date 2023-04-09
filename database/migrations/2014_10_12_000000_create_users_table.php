<?php

use App\Models\Departement;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firt_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('nationality');
            $table->string('national_id');
            $table->string('picture', 255)->nullable();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('situation', ['single', 'married', 'divorced', 'widowed']);
            $table->string('spouse_name')->nullable();
            $table->integer('children')->nullable();
            $table->string('job_title');
            $table->foreignIdFor(Departement::class);
            $table->date('date_of_joining');
            $table->date('date_of_leaving')->nullable();
            $table->enum('role', ['user', 'admin']);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
