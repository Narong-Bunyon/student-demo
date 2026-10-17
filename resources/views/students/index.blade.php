@extends('layouts.app')

@section('title', 'Students Directory')
@section('page-title', 'Students')

@section('content')
<div class="card card-custom mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Student Directory</h4>
                <p class="text-muted small mb-0">Browse, filter, search, and manage all enrolled students</p>
            </div>
            <div>
                <a href="{{ route('students.create') }}" class="btn btn-brand d-inline-flex align-items-center gap-2">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Register Student</span>
                </a>
            </div>
        </div>

        <hr class="my-4">

        <!-- Filters Form -->
        <form method="GET" action="{{ route('students.index') }}" class="row g-3">
            <!-- Search field -->
            <div class="col-12 col-md-5">
                <label class="form-label small fw-semibold text-muted">Search Query</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, student code..." value="{{ $search }}">
                </div>
            </div>

            <!-- Course filter -->
            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label small fw-semibold text-muted">Filter by Course</label>
                <select name="course_id" class="form-select">
                    <option value="">All Courses</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ $courseId == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status filter -->
            <div class="col-12 col-sm-6 col-md-2">
                <label class="form-label small fw-semibold text-muted">Filter by Status</label>
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Submit buttons -->
            <div class="col-12 col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-secondary w-100">Apply</button>
                @if($search || $courseId || $status)
                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Student Name</th>
                        <th>Email & Phone</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th class="text-end" style="width: 160px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">{{ $student->student_code }}</span>
                            </td>
                            <td>
                                <a href="{{ route('students.show', $student) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $student->full_name }}
                                </a>
                                @if($student->gender)
                                    <div class="text-muted extra-small" style="font-size: 0.75rem;">{{ $student->gender }}</div>
                                @endif
                            </td>
                            <td>
                                <div><i class="bi bi-envelope me-1 text-muted"></i>{{ $student->email }}</div>
                                @if($student->phone)
                                    <div class="text-muted small"><i class="bi bi-telephone me-1 text-muted"></i>{{ $student->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('courses.show', $student->course_id) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none">
                                    {{ $student->course->name ?? 'None' }}
                                </a>
                            </td>
                            <td>
                                @if($student->status === 'active')
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-dot"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        <i class="bi bi-dot"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $student->created_at->format('M d, Y') }}
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('students.show', $student) }}" class="btn btn-light border" title="View details">
                                        <i class="bi bi-eye text-primary"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-light border" title="Edit student">
                                        <i class="bi bi-pencil text-secondary"></i>
                                    </a>
                                    <button type="button" class="btn btn-light border text-danger" title="Delete student"
                                            data-bs-toggle="modal" data-bs-target="#deleteStudentModal{{ $student->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteStudentModal{{ $student->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered text-start">
                                        <div class="modal-content">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Delete Student Record</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                Are you sure you want to delete student <strong>{{ $student->full_name }}</strong> ({{ $student->student_code }})?
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('students.destroy', $student) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 text-secondary"></i>
                                No students found matching your filters.
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
@endsection
