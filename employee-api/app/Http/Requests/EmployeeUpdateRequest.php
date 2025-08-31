<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // apply authorization/policies elsewhere if needed
    }

    public function rules(): array
    {
        // The route parameter may be a model instance or the id string
        $employee = $this->route('employee');
        $id = $employee instanceof \App\Models\Employee ? $employee->id : $employee;

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($id, 'id'),
            ],
            'department_id' => ['sometimes', 'nullable', 'exists:departments,id'],
            'detail.designation' => ['sometimes', 'string', 'max:255'],
            'detail.salary' => ['sometimes', 'numeric', 'min:0'],
            'detail.address' => ['sometimes', 'nullable', 'string', 'max:500'],
            'detail.joined_date' => ['sometimes', 'date'],
        ];
    }
}
