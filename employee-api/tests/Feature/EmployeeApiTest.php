<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');

        // Create some departments
        Department::factory()->count(3)->create();
    }

    /** @test */
    public function it_can_list_employees_with_pagination()
    {
        Employee::factory()->count(15)->create();

        $response = $this->getJson('/api/employees?per_page=10');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'email', 'created_at', 'detail', 'department']
                     ],
                     'links',
                     'meta',
                 ]);
    }

    /** @test */
    public function it_can_search_employees_by_name_or_email()
    {
        Employee::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $response = $this->getJson('/api/employees?q=John');
        $response->assertStatus(200);
        $this->assertEquals('John Doe', $response->json('data.0.name'));

        $response = $this->getJson('/api/employees?q=john@example.com');
        $response->assertStatus(200);
        $this->assertEquals('John Doe', $response->json('data.0.name'));
    }

    /** @test */
    public function it_can_filter_employees_by_department()
    {
        $dept = Department::first();
        Employee::factory()->count(5)->create(['department_id' => $dept->id]);

        $response = $this->getJson("/api/employees?department_id={$dept->id}");
        $response->assertStatus(200);

        foreach ($response->json('data') as $emp) {
            $this->assertEquals($dept->id, $emp['department']['id']);
        }
    }

    /** @test */
    public function it_can_filter_employees_by_salary_range()
    {
        $emp1 = Employee::factory()->create();
        $emp1->detail()->create(['salary' => 5000, 'designation' => 'Dev', 'joined_date' => now()]);

        $emp2 = Employee::factory()->create();
        $emp2->detail()->create(['salary' => 10000, 'designation' => 'Manager', 'joined_date' => now()]);

        $response = $this->getJson('/api/employees?salary_min=6000&salary_max=15000');
        $response->assertStatus(200);

        foreach ($response->json('data') as $emp) {
            $this->assertTrue($emp['detail']['salary'] >= 6000 && $emp['detail']['salary'] <= 15000);
        }
    }

    /** @test */
    public function it_can_create_an_employee()
    {
        $dept = Department::first();

        $payload = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'department_id' => $dept->id,
            'detail' => [
                'designation' => 'Designer',
                'salary' => 7000,
                'joined_date' => now()->toDateString(),
            ],
        ];

        $response = $this->postJson('/api/employees', $payload);
        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Jane Doe', 'email' => 'jane@example.com']);
    }

    /** @test */
    public function it_can_show_single_employee()
    {
        $employee = Employee::factory()->create();
        $employee->detail()->create(['salary' => 8000, 'designation' => 'QA', 'joined_date' => now()]);

        $response = $this->getJson("/api/employees/{$employee->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $employee->id]);
    }

    /** @test */
    public function it_can_update_an_employee()
    {
        $employee = Employee::factory()->create();
        $employee->detail()->create(['salary' => 5000, 'designation' => 'Dev', 'joined_date' => now()]);

        $payload = [
            'name' => 'Updated Name',
            'detail' => [
                'salary' => 9000,
            ],
        ];

        $response = $this->putJson("/api/employees/{$employee->id}", $payload);
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Name', 'salary' => 9000]);
    }

    /** @test */
    public function it_can_soft_delete_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/employees/{$employee->id}");
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Employee soft-deleted.']);

        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
    }
}
