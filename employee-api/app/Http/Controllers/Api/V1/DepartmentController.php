<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $departments = Department::select('id', 'name', 'description')
            ->orderBy('name')
            ->get();

        return response()->json($departments);
    }
}
