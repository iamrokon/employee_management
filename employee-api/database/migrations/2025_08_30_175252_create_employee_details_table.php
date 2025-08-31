<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->id(); // int PK
            $table->uuid('employee_id');
            $table->string('designation');
            $table->decimal('salary', 12, 2);
            $table->string('address')->nullable();
            $table->date('joined_date');
            $table->timestamps();


            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnDelete();
            $table->index('salary');
            $table->index('joined_date');
            $table->index('employee_id');
        });
    }
    public function down(): void {
        Schema::dropIfExists('employee_details');
    }
};
