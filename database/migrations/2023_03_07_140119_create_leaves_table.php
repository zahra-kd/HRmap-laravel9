<?php

use App\Models\User;
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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->enum('leave_type', ['Sick Leave', 'Casual Leave', 'Maternity Leave', 'Unpaid Leave', 'Bereavement Leave']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days');
            $table->enum('status', ['approved', 'rejected', 'pending']);
            $table->mediumText('description');
            $table->foreignIdFor(User::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
