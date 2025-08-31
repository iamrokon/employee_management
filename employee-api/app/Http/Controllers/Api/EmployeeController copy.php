<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    /**
     * List employees with search, filter, sort and pagination.
     */
    public function index(Request $request)
    {
        $perPage = min((int)$request->input('per_page', 20), 100);

        $query = Employee::query()
            ->with(['department:id,name', 'detail:employee_id,designation,salary,joined_date'])
            ->select('employees.id', 'employees.name', 'employees.email', 'employees.department_id', 'employees.created_at');

        // search q -> name or email
        if ($s = $request->input('q')) {
            $s = trim($s);
            if ($s !== '') {
                $query->where(function ($q) use ($s) {
                    $q->where('employees.name', 'like', "%{$s}%")
                      ->orWhere('employees.email', 'like', "%{$s}%");
                });
            }
        }

        // department filter
        if ($dept = $request->input('department_id')) {
            $query->where('employees.department_id', $dept);
        }

        // Join employee_details for salary/joined filters and sorting
        $query->leftJoin('employee_details as ed', 'ed.employee_id', '=', 'employees.id');

        if (($min = $request->input('salary_min')) !== null) {
            $query->where('ed.salary', '>=', $min);
        }
        if (($max = $request->input('salary_max')) !== null) {
            $query->where('ed.salary', '<=', $max);
        }
        if ($jf = $request->input('joined_from')) {
            $query->where('ed.joined_date', '>=', $jf);
        }
        if ($jt = $request->input('joined_to')) {
            $query->where('ed.joined_date', '<=', $jt);
        }

        $sortableMap = [
            'joined_date' => 'ed.joined_date',
            'name' => 'employees.name',
            'salary' => 'ed.salary',
        ];
        $sortKey = $sortableMap[$request->input('sort', 'joined_date')] ?? 'ed.joined_date';
        $order = $request->input('order', 'desc') === 'asc' ? 'asc' : 'desc';

        $query->orderBy($sortKey, $order)->orderBy('employees.id');

        $paginator = $query->paginate($perPage)->appends($request->query());

        // Return a resource collection; Laravel's resource pagination will be preserved.
        return EmployeeResource::collection($paginator);
    }

    /**
     * Create employee with detail (transactional).
     */
    public function store(EmployeeStoreRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $emp = Employee::create($request->only(['name', 'email', 'department_id']));
            $emp->detail()->create($request->input('detail'));
            return new EmployeeResource($emp->load(['department', 'detail']));
        });
    }

    /**
     * Show single employee.
     */
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee->load(['department', 'detail']));
    }

    /**
     * Update employee and detail.
     */
    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        return DB::transaction(function () use ($request, $employee) {
            $employee->update($request->only(['name', 'email', 'department_id']));

            if ($request->has('detail')) {
                $employee->detail()->updateOrCreate(
                    ['employee_id' => $employee->id],
                    $request->input('detail')
                );
            }

            return new EmployeeResource($employee->load(['department', 'detail']));
        });
    }

    /**
     * Soft delete employee.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return response()->json(['message' => 'Employee soft-deleted.']);
    }
}
