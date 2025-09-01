<?php

namespace App\Services\Api\V1;

use App\Models\Employee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * List employees with search, filter, sort and pagination.
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $perPage = min((int)$request->input('per_page', 20), 100);

        $query = Employee::query()
            ->with(['department:id,name', 'detail:employee_id,designation,salary,joined_date'])
            ->select('employees.id', 'employees.name', 'employees.email', 'employees.department_id', 'employees.created_at');

        // Search by name or email
        if ($s = $request->input('q')) {
            $s = trim($s);
            if ($s !== '') {
                $query->where(function ($q) use ($s) {
                    $q->where('employees.name', 'like', "{$s}%")
                      ->orWhere('employees.email', 'like', "{$s}%");
                });
            }
        }

        // Department filter
        if ($dept = $request->input('department_id')) {
            $query->where('employees.department_id', $dept);
        }

        // Join employee_details for salary filter and joining date sorting
        $query->leftJoin('employee_details as ed', 'ed.employee_id', '=', 'employees.id');

        if (($min = $request->input('salary_min')) !== null) {
            $query->where('ed.salary', '>=', $min);
        }
        if (($max = $request->input('salary_max')) !== null) {
            $query->where('ed.salary', '<=', $max);
        }

        // Sorting: default by created_at desc, optional by joined_date
        $sort = $request->input('sort', 'created_at'); // either 'created_at' or 'joined_date'
        $order = $request->input('order', 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sort === 'joined_date') {
            $query->orderBy('ed.joined_date', $order);
        } else {
            $query->orderBy('employees.created_at', $order);
        }

        return $query->paginate($perPage)->appends($request->query());
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

            return $employee->load(['department', 'detail']);
        });
    }

    /**
     * Soft delete employee.
     */
    public function destroy(Employee $employee): void
    {
        $employee->delete();
    }
}
