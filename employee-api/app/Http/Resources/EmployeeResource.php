<?php

namespace App\Http\Resources;

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
            'department' => $this->whenLoaded('department', function () {
                return [
                    'id' => $this->department->id,
                    'name' => $this->department->name,
                ];
            }),
            'detail' => $this->whenLoaded('detail', function () {
                return [
                    'designation' => $this->detail->designation ?? null,
                    'salary' => isset($this->detail->salary) ? (float) $this->detail->salary : null,
                    'joined_date' => $this->detail->joined_date ?? null,
                    'address' => $this->detail->address ?? null,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
