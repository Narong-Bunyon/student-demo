<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Default Admin User
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Seed Sample Courses
        $coursesData = [
            [
                'name' => 'Computer Science & Software Engineering',
                'description' => 'Comprehensive study of computer architecture, algorithms, data structures, and modern software design.',
            ],
            [
                'name' => 'Information Technology & Cloud Systems',
                'description' => 'Focuses on networking, DevOps practices, Linux server administration, and scalable cloud infrastructures.',
            ],
            [
                'name' => 'Cybersecurity & Network Defense',
                'description' => 'Hands-on training in security fundamentals, ethical hacking, digital forensics, and network auditing.',
            ],
            [
                'name' => 'Data Science & Artificial Intelligence',
                'description' => 'Machine learning models, statistics, Python data pipelines, and predictive analytics.',
            ],
            [
                'name' => 'Business Administration & Management',
                'description' => 'Organizational leadership, business analytics, financial planning, and project management.',
            ],
        ];

        $courses = [];
        foreach ($coursesData as $c) {
            $courses[] = Course::updateOrCreate(['name' => $c['name']], $c);
        }

        // 3. Seed Sample Students with hashed password for student portal access
        $defaultStudentPassword = Hash::make('password');

        $sampleStudents = [
            [
                'student_code' => 'STU-2026-001',
                'first_name' => 'Alex',
                'last_name' => 'Morgan',
                'email' => 'alex.morgan@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 019-2834',
                'date_of_birth' => '2002-05-14',
                'gender' => 'Male',
                'course_id' => $courses[0]->id,
                'status' => 'active',
            ],
            [
                'student_code' => 'STU-2026-002',
                'first_name' => 'Sophia',
                'last_name' => 'Chen',
                'email' => 'sophia.chen@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 014-9982',
                'date_of_birth' => '2003-08-22',
                'gender' => 'Female',
                'course_id' => $courses[0]->id,
                'status' => 'active',
            ],
            [
                'student_code' => 'STU-2026-003',
                'first_name' => 'Liam',
                'last_name' => 'Nguyen',
                'email' => 'liam.nguyen@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 018-7721',
                'date_of_birth' => '2001-11-30',
                'gender' => 'Male',
                'course_id' => $courses[1]->id,
                'status' => 'active',
            ],
            [
                'student_code' => 'STU-2026-004',
                'first_name' => 'Emma',
                'last_name' => 'Watson',
                'email' => 'emma.watson@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 012-3498',
                'date_of_birth' => '2002-01-18',
                'gender' => 'Female',
                'course_id' => $courses[2]->id,
                'status' => 'active',
            ],
            [
                'student_code' => 'STU-2026-005',
                'first_name' => 'Daniel',
                'last_name' => 'Kim',
                'email' => 'daniel.kim@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 017-6643',
                'date_of_birth' => '2000-09-05',
                'gender' => 'Male',
                'course_id' => $courses[3]->id,
                'status' => 'inactive',
            ],
            [
                'student_code' => 'STU-2026-006',
                'first_name' => 'Isabella',
                'last_name' => 'Rossi',
                'email' => 'isabella.rossi@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 013-8812',
                'date_of_birth' => '2003-03-12',
                'gender' => 'Female',
                'course_id' => $courses[4]->id,
                'status' => 'active',
            ],
            [
                'student_code' => 'STU-2026-007',
                'first_name' => 'Marcus',
                'last_name' => 'Aurelius',
                'email' => 'marcus.a@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 011-5529',
                'date_of_birth' => '2002-07-09',
                'gender' => 'Male',
                'course_id' => $courses[1]->id,
                'status' => 'inactive',
            ],
            [
                'student_code' => 'STU-2026-008',
                'first_name' => 'Olivia',
                'last_name' => 'Taylor',
                'email' => 'olivia.taylor@example.com',
                'password' => $defaultStudentPassword,
                'phone' => '+1 (555) 016-4437',
                'date_of_birth' => '2003-12-01',
                'gender' => 'Female',
                'course_id' => $courses[3]->id,
                'status' => 'active',
            ],
        ];

        foreach ($sampleStudents as $student) {
            Student::updateOrCreate(['student_code' => $student['student_code']], $student);
        }
    }
}
