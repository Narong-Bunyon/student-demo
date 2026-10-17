<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->course = Course::create([
            'name' => 'Software Engineering',
            'description' => 'Software design, testing, and architecture.',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_dashboard_displays_counts(): void
    {
        Student::create([
            'student_code' => 'STU-001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        Student::create([
            'student_code' => 'STU-002',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'course_id' => $this->course->id,
            'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->user)->get('/');

        $response->assertOk();
        $response->assertSee('John Doe');
        $response->assertSee('Software Engineering');
    }

    public function test_user_can_create_a_course(): void
    {
        $response = $this->actingAs($this->user)->post('/courses', [
            'name' => 'DevOps & Cloud Architecture',
            'description' => 'CI/CD, Docker, Kubernetes, and AWS',
        ]);

        $response->assertRedirect('/courses');
        $this->assertDatabaseHas('courses', [
            'name' => 'DevOps & Cloud Architecture',
        ]);
    }

    public function test_user_cannot_delete_a_course_with_enrolled_students(): void
    {
        Student::create([
            'student_code' => 'STU-100',
            'first_name' => 'Active',
            'last_name' => 'Student',
            'email' => 'active.student@example.com',
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->delete("/courses/{$this->course->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('courses', [
            'id' => $this->course->id,
        ]);
    }

    public function test_user_can_create_student(): void
    {
        $response = $this->actingAs($this->user)->post('/students', [
            'student_code' => 'STU-NEW-01',
            'first_name' => 'Alice',
            'last_name' => 'Wonderland',
            'email' => 'alice@example.com',
            'phone' => '1234567890',
            'date_of_birth' => '2001-05-15',
            'gender' => 'Female',
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response->assertRedirect('/students');
        $this->assertDatabaseHas('students', [
            'student_code' => 'STU-NEW-01',
            'email' => 'alice@example.com',
        ]);
    }

    public function test_duplicate_student_code_and_email_are_rejected(): void
    {
        Student::create([
            'student_code' => 'STU-DUP-01',
            'first_name' => 'First',
            'last_name' => 'Person',
            'email' => 'duplicate@example.com',
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->post('/students', [
            'student_code' => 'STU-DUP-01',
            'first_name' => 'Second',
            'last_name' => 'Person',
            'email' => 'duplicate@example.com',
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors(['student_code', 'email']);
    }

    public function test_user_can_search_and_filter_students(): void
    {
        Student::create([
            'student_code' => 'STU-SEARCH-01',
            'first_name' => 'Roberta',
            'last_name' => 'Williams',
            'email' => 'roberta@example.com',
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        Student::create([
            'student_code' => 'STU-SEARCH-02',
            'first_name' => 'Carlos',
            'last_name' => 'Santana',
            'email' => 'carlos@example.com',
            'course_id' => $this->course->id,
            'status' => 'inactive',
        ]);

        // Search by name
        $response = $this->actingAs($this->user)->get('/students?search=Roberta');
        $response->assertOk();
        $response->assertSee('Roberta Williams');
        $response->assertDontSee('Carlos Santana');

        // Filter by inactive status
        $statusResponse = $this->actingAs($this->user)->get('/students?status=inactive');
        $statusResponse->assertOk();
        $statusResponse->assertSee('Carlos Santana');
        $statusResponse->assertDontSee('Roberta Williams');
    }

    public function test_user_can_delete_student(): void
    {
        $student = Student::create([
            'student_code' => 'STU-DEL-01',
            'first_name' => 'To',
            'last_name' => 'Delete',
            'email' => 'delete.me@example.com',
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->user)->delete("/students/{$student->id}");
        $response->assertRedirect('/students');
        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }
}
