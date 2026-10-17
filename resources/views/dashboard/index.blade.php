@extends('layouts.app')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Stat 1: Total Students -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase">Total Students</span>
                <div class="stat-icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline">
                <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalStudents) }}</h2>
                <span class="badge bg-primary-subtle text-primary ms-2 rounded-pill small">Enrolled</span>
            </div>
            <div class="mt-3 text-muted small">
                <a href="{{ route('students.index') }}" class="text-decoration-none fw-medium text-primary">
                    View all students <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat 2: Total Courses -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase">Total Courses</span>
                <div class="stat-icon-box bg-info-subtle text-info">
                    <i class="bi bi-journal-code"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline">
                <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalCourses) }}</h2>
                <span class="badge bg-info-subtle text-info ms-2 rounded-pill small">Active catalog</span>
            </div>
            <div class="mt-3 text-muted small">
                <a href="{{ route('courses.index') }}" class="text-decoration-none fw-medium text-info">
                    Manage courses <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat 3: Active Students -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase">Active Students</span>
                <div class="stat-icon-box bg-success-subtle text-success">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline">
                <h2 class="fw-bold mb-0 text-success">{{ number_format($activeStudents) }}</h2>
                <span class="badge bg-success-subtle text-success ms-2 rounded-pill small">
                    @if($totalStudents > 0)
                        {{ round(($activeStudents / $totalStudents) * 100) }}%
                    @else
                        0%
                    @endif
                </span>
            </div>
            <div class="mt-3 text-muted small">
                <a href="{{ route('students.index', ['status' => 'active']) }}" class="text-decoration-none fw-medium text-success">
                    Filter active list <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat 4: Inactive Students -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="text-muted fw-semibold small text-uppercase">Inactive Students</span>
                <div class="stat-icon-box bg-warning-subtle text-warning">
                    <i class="bi bi-pause-circle-fill"></i>
                </div>
            </div>
            <div class="d-flex align-items-baseline">
                <h2 class="fw-bold mb-0 text-warning">{{ number_format($inactiveStudents) }}</h2>
                <span class="badge bg-warning-subtle text-warning ms-2 rounded-pill small">
                    @if($totalStudents > 0)
                        {{ round(($inactiveStudents / $totalStudents) * 100) }}%
                    @else
                        0%
                    @endif
                </span>
            </div>
            <div class="mt-3 text-muted small">
                <a href="{{ route('students.index', ['status' => 'inactive']) }}" class="text-decoration-none fw-medium text-warning">
                    Filter inactive list <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Students Table -->
    <div class="col-12 col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Recently Enrolled Students</h5>
                    <p class="text-muted small mb-0">Latest additions to the academic system</p>
                </div>
                <a href="{{ route('students.create') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-lg me-1"></i> Add Student
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStudents as $student)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace">{{ $student->student_code }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $student->full_name }}</div>
                                        <div class="text-muted small">{{ $student->email }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            {{ $student->course->name ?? 'Unassigned' }}
                                        </span>
                                    </td>
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
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        No students registered yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Courses by Enrollment -->
    <div class="col-12 col-lg-5">
        <div class="card card-custom h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Courses by Enrollment</h5>
                    <p class="text-muted small mb-0">Highest student participation</p>
                </div>
                <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-secondary">
                    View All
                </a>
            </div>
            <div class="card-body p-4">
                @forelse($coursesWithCount as $course)
                    <div class="mb-4 last-mb-0">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-semibold text-dark">{{ $course->name }}</span>
                            <span class="badge bg-primary rounded-pill">{{ $course->students_count }} students</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php
                                $percent = $totalStudents > 0 ? ($course->students_count / $totalStudents) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">
                        No courses available yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
