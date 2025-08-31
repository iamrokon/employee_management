<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:employees,email'],
            'department_id' => ['nullable','exists:departments,id'],
            'detail.designation' => ['required','string','max:255'],
            'detail.salary' => ['required','numeric','min:0'],
            'detail.address' => ['nullable','string','max:500'],
            'detail.joined_date' => ['required','date'],
        ];
    }
}
