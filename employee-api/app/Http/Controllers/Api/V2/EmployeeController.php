<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EmployeeStoreRequest;
use App\Http\Requests\Api\V1\EmployeeUpdateRequest;
use App\Http\Resources\Api\V2\EmployeeResource as V2EmployeeResource;
use App\Http\Resources\Api\V1\EmployeeResource;
use App\Models\Employee;
use App\Services\Api\V2\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    private EmployeeService $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $employees = $this->service->index($request);
        return V2EmployeeResource::collection($employees);
    }

    public function store(EmployeeStoreRequest $request)
    {
        $employee = $this->service->store($request->validated());
        return new EmployeeResource($employee);
    }

    public function show(Employee $employee)
    {
        $employee = $this->service->show($employee);
        return new EmployeeResource($employee);
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $employee = $this->service->update($employee, $request->validated());
        return new EmployeeResource($employee);
    }

    public function destroy(Employee $employee)
    {
        $this->service->destroy($employee);
        return response()->json(['message' => 'Employee soft-deleted.']);
    }
}
