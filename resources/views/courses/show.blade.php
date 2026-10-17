@extends('layouts.app')

@section('title', 'Course Details: ' . $course->name)
@section('page-title', 'Course Details')

@section('content')
<div class="row g-4 mb-4">
    <!-- Course Info Card -->
    <div class="col-12 col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">
                        Course Profile
                    </span>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                            <li><a class="dropdown-item" href="{{ route('courses.edit', $course) }}"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                        </ul>
                    </div>
                </div>

                <h3 class="fw-bold text-dark mb-2">{{ $course->name }}</h3>
                <p class="text-muted mb-4">{{ $course->description ?? 'No description has been added for this course.' }}</p>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Course ID</span>
                        <span class="fw-bold text-dark font-monospace">#{{ $course->id }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Total Enrolled</span>
                        <span class="badge bg-primary rounded-pill">{{ $course->students_count }} Students</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Created Date</span>
                        <span class="text-dark small">{{ $course->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('students.create', ['course_id' => $course->id]) }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-person-plus me-1"></i> Enroll Student Here
                    </a>
                    <a href="{{ route('courses.index') }}" class="btn btn-light border w-100">
                        <i class="bi bi-arrow-left me-1"></i> Back to Courses
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Enrolled Students List -->
    <div class="col-12 col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Enrolled Students</h5>
                    <p class="text-muted small mb-0">Students currently attending this course</p>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Student Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr>
                                    <td><span class="badge bg-light text-dark border font-monospace">{{ $student->student_code }}</span></td>
                                    <td class="fw-bold text-dark">{{ $student->full_name }}</td>
                                    <td class="text-muted small">{{ $student->email }}</td>
                                    <td>
                                        @if($student->status === 'active')
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-light border text-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        No students enrolled in this course yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($students->hasPages())
                <div class="card-footer bg-white border-top p-3">
                    {{ $students->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
