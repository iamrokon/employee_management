<?php

namespace App\Services\Api\V2;

use App\Models\Employee;
use Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class EmployeeService
{
    /**
     * List employees with search, filter, sort and pagination.
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $perPage = min((int) $request->input('per_page', 20), 100);

        // Generate a unique cache key based on query parameters
        $cacheKey = 'employees_'
            . md5(json_encode($request->query()) . ":page_" . $request->input('page', 1));

        return Cache::tags(['employees'])->remember($cacheKey, 60, function () use ($request, $perPage) {
            $query = \DB::table('employees as e')
                ->leftJoin('departments as d', 'd.id', '=', 'e.department_id')
                ->leftJoin('employee_details as ed', 'ed.employee_id', '=', 'e.id')
                ->select(
                    'e.id',
                    'e.name',
                    'e.email',
                    'e.department_id',
                    'd.name as department_name',
                    'ed.designation',
                    'ed.salary',
                    'ed.joined_date',
                    'ed.address',
                    'e.created_at',
                    'e.updated_at'
                )
                ->whereNull('e.deleted_at');

            // Search by name or email
            if ($s = $request->input('q')) {
                $s = trim($s);
                if ($s !== '') {
                    $query->where(function ($q) use ($s) {
                        $q->where('e.name', 'like', "{$s}%")
                            ->orWhere('e.email', 'like', "{$s}%");
                    });
                }
            }

            // Department filter
            if ($dept = $request->input('department_id')) {
                $query->where('e.department_id', $dept);
            }

            // Salary filter
            if (($min = $request->input('salary_min')) !== null) {
                $query->where('ed.salary', '>=', $min);
            }
            if (($max = $request->input('salary_max')) !== null) {
                $query->where('ed.salary', '<=', $max);
            }

            // Sorting
            $sort = $request->input('sort', 'created_at');
            $order = $request->input('order', 'desc') === 'asc' ? 'asc' : 'desc';
            if ($sort === 'joined_date') {
                $query->orderBy('ed.joined_date', $order);
            } else {
                $query->orderBy('e.created_at', $order);
            }

            return $query->paginate($perPage)->appends($request->query());
        });
    }

    /**
     * Create a new employee with detail.
     */
    public function store(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            $emp = Employee::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'department_id' => $data['department_id'],
            ]);

            if (isset($data['detail'])) {
                $emp->detail()->create($data['detail']);
            }
            Cache::tags(['employees'])->flush();

            return $emp->load(['department', 'detail']);
        });
    }

    /**
     * Show single employee with relations.
     */
    public function show(Employee $employee): Employee
    {
        return $employee->load(['department', 'detail']);
    }

    /**
     * Update employee and detail.
     */
    public function update(Employee $employee, array $data): Employee
    {
        return DB::transaction(function () use ($employee, $data) {
            $employee->update([
                'name' => $data['name'] ?? $employee->name,
                'email' => $data['email'] ?? $employee->email,
                'department_id' => $data['department_id'] ?? $employee->department_id,
            ]);

            if (isset($data['detail'])) {
                $employee->detail()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    $data['detail']
                );
            }
            Cache::tags(['employees'])->flush();

            return $employee->load(['department', 'detail']);
        });
    }

    /**
     * Soft delete employee.
     */
    public function destroy(Employee $employee): void
    {
        // $this->clearEmployeeCache();
        Cache::tags(['employees'])->flush();
        $employee->delete();
    }
    // private function clearEmployeeCache(): void
    // {
    //     $prefix = config('cache.prefix') ? config('cache.prefix') . ':' : '';
    //     $keys = Redis::keys($prefix . 'employees_*');

    //     foreach ($keys as $key) {
    //         Redis::del($key);
    //     }
    // }
}
