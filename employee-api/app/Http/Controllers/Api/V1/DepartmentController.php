<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Cache;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $cacheKey = 'departments_all';

        // Cache for 60 minutes
        $departments = Cache::remember($cacheKey, 60 * 60, function () {
            return Department::select('id', 'name', 'description')
                ->orderBy('name')
                ->get();
        });

        return response()->json($departments);
    }
}
