<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('department_id')
                ->references('id')->on('departments')
                ->nullOnDelete();

            // Useful indexes
            $table->index('name');
            $table->index('email');
            $table->index('department_id');
        });

        // Composite index for soft delete + sorting
        DB::statement('CREATE INDEX idx_employees_deleted_created
                    ON employees (deleted_at ASC, created_at DESC)');
    }
    public function down(): void {
        Schema::dropIfExists('employees');
        DB::statement('DROP INDEX IF EXISTS idx_employees_deleted_created ON employees');
    }
};
