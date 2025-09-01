<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string,mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            'department' => $this->department_name ? [
                'id' => $this->department_id,
                'name' => $this->department_name,
            ] : null,

            'detail' => isset($this->designation) ? [
                'designation' => $this->designation,
                'salary' => isset($this->salary) ? (float) $this->salary : null,
                'joined_date' => $this->joined_date,
                'address' => $this->address ?? null,
            ] : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
