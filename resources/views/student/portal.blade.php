<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Student Portal - {{ config('app.name', 'student-demo') }}</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-body">

    <!-- Student Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top border-bottom border-dark-subtle shadow-sm py-3" style="background: linear-gradient(90deg, #1e1b4b 0%, #0f172a 100%) !important;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('student.portal') }}">
                <span class="d-inline-flex align-items-center justify-content-center p-2 rounded-3 text-white" style="background: #4f46e5; width: 36px; height: 36px;">
                    <i class="bi bi-mortarboard-fill"></i>
                </span>
                <span>Student<span class="text-primary" style="color: #818cf8 !important;">Portal</span></span>
            </a>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block text-white">
                    <div class="d-flex align-items-center gap-2 justify-content-end">
                        <div class="fw-semibold small">{{ $student->full_name }}</div>
                        <span class="badge bg-success-subtle text-success text-uppercase" style="font-size: 0.65rem;">
                            {{ $student->role ?? 'student' }}
                        </span>
                    </div>
                    <div class="text-muted small font-monospace">{{ $student->student_code }}</div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3">
                        <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill fs-5 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="p-4 p-md-5 mb-4 rounded-4 text-white shadow-sm" style="background: linear-gradient(135deg, #312e81 0%, #4338ca 50%, #4f46e5 100%);">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill">
                            <i class="bi bi-person-check-fill me-1"></i> Enrolled Student
                        </span>
                        <span class="badge bg-white bg-opacity-25 text-white fw-semibold px-3 py-2 rounded-pill text-uppercase" style="font-size: 0.75rem;">
                            Role: {{ $student->role ?? 'student' }}
                        </span>
                    </div>
                    <h2 class="fw-bold mb-2">Welcome, {{ $student->full_name }}!</h2>
                    <p class="lead mb-0 text-white-50">Here is your enrolled academic profile, course cohort details, and student information.</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-3 d-inline-block text-start">
                        <div class="small text-white-50">Student Code:</div>
                        <div class="fs-5 fw-bold font-monospace text-white">{{ $student->student_code }}</div>
                        <div class="small text-white-50 mt-1">Status:</div>
                        <span class="badge bg-success rounded-pill px-2.5">Active Enrollment</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Enrolled Course Information Card -->
            <div class="col-12 col-lg-7">
                <div class="card card-custom h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill">
                            Current Program
                        </span>
                        <h4 class="fw-bold text-dark mt-2 mb-1">{{ $student->course->name }}</h4>
                        <p class="text-muted small">Course syllabus and program description</p>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <p class="text-secondary mb-4">
                            {{ $student->course->description ?? 'No specific course description available.' }}
                        </p>

                        <div class="row g-3 p-3 bg-light rounded-3 mb-4">
                            <div class="col-6 col-md-4">
                                <span class="text-muted extra-small" style="font-size: 0.75rem;">DEPARTMENT ID</span>
                                <div class="fw-bold text-dark font-monospace">#CRS-{{ $student->course->id }}</div>
                            </div>
                            <div class="col-6 col-md-4">
                                <span class="text-muted extra-small" style="font-size: 0.75rem;">TOTAL PEERS</span>
                                <div class="fw-bold text-dark">{{ $student->course->students()->count() }} Students</div>
                            </div>
                            <div class="col-12 col-md-4">
                                <span class="text-muted extra-small" style="font-size: 0.75rem;">ENROLLMENT DATE</span>
                                <div class="fw-bold text-dark">{{ $student->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-people me-1 text-primary"></i> Classmates in this Course</h6>
                        <div class="row g-2">
                            @forelse($classmates as $peer)
                                <div class="col-12 col-sm-6">
                                    <div class="p-2.5 border rounded-3 d-flex align-items-center gap-2.5 bg-white">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary fw-bold" style="width: 36px; height: 36px;">
                                            {{ strtoupper(substr($peer->first_name, 0, 1)) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-semibold small text-truncate">{{ $peer->full_name }}</div>
                                            <div class="text-muted extra-small font-monospace" style="font-size: 0.75rem;">{{ $peer->student_code }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-muted small">No other students in this course yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Profile Information Card -->
            <div class="col-12 col-lg-5">
                <div class="card card-custom h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1 rounded-pill">
                            Profile Details
                        </span>
                        <h4 class="fw-bold text-dark mt-2 mb-1">My Information</h4>
                        <p class="text-muted small">Personal details recorded in student registry</p>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="list-group list-group-flush border-top">
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-envelope me-2 text-primary"></i>Email Address</span>
                                <span class="fw-semibold text-dark">{{ $student->email }}</span>
                            </div>
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-telephone me-2 text-primary"></i>Phone Number</span>
                                <span class="fw-semibold text-dark">{{ $student->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-calendar3 me-2 text-primary"></i>Date of Birth</span>
                                <span class="fw-semibold text-dark">{{ $student->date_of_birth ? $student->date_of_birth->format('F d, Y') : 'Not specified' }}</span>
                            </div>
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-gender-ambiguous me-2 text-primary"></i>Gender</span>
                                <span class="fw-semibold text-dark">{{ $student->gender ?? 'Not specified' }}</span>
                            </div>
                            <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-patch-check me-2 text-primary"></i>Student Status</span>
                                <span class="badge bg-success-subtle text-success">Active</span>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded-3 text-muted small">
                            <i class="bi bi-info-circle me-1 text-primary"></i> To request updates to your course or personal data, please contact the Administration Office.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
