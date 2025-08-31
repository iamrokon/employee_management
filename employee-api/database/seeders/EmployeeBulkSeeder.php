<?php
namespace Database\Seeders;


use App\Models\Employee;
use App\Models\EmployeeDetail;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class EmployeeBulkSeeder extends Seeder
{
    public function run(): void
    {
        $target = 100_000;
        $deptIds = Department::pluck('id')->all();


        $now = now();
        $empChunkSize = 5000; // tune based on memory
        $detailChunk = [];


        for ($i = 0; $i < $target; $i += $empChunkSize) {
            $batch = [];
            for ($j = 0; $j < $empChunkSize && ($i + $j) < $target; $j++) {
                $id = (string) Str::uuid();
                $batch[] = [
                    'id' => $id,
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'department_id' => Arr::random($deptIds),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
                $detailChunk[] = [
                    'employee_id' => $id,
                    'designation' => fake()->jobTitle(),
                    'salary' => fake()->numberBetween(25000, 250000),
                    'address' => fake()->address(),
                    'joined_date' => fake()->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }


            DB::table('employees')->insert($batch);
            // insert details in smaller chunks to avoid large packets
            foreach (array_chunk($detailChunk, 2500) as $d) {
                DB::table('employee_details')->insert($d);
            }
            $detailChunk = [];
        }
    }
}
