<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // handle policies/guards elsewhere if needed
    }

    public function rules(): array
    {
        // The route parameter may be a model instance or an id
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
