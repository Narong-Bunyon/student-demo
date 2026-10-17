@extends('layouts.app')

@section('title', 'Courses Management')
@section('page-title', 'Courses')

@section('content')
<div class="card card-custom mb-4">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-1 text-dark">Course Catalog</h4>
                <p class="text-muted small mb-0">Manage and monitor academic programs and student capacities</p>
            </div>
            <div>
                <a href="{{ route('courses.create') }}" class="btn btn-brand d-inline-flex align-items-center gap-2">
                    <i class="bi bi-plus-lg"></i>
                    <span>Create Course</span>
                </a>
            </div>
        </div>

        <hr class="my-4">

        <!-- Search Bar -->
        <form method="GET" action="{{ route('courses.index') }}" class="row g-2">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name or description..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-secondary">Filter</button>
                @if($search)
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th style="width: 80px;">ID</th>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Enrolled Students</th>
                        <th>Created Date</th>
                        <th class="text-end" style="width: 180px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="text-muted fw-mono">#{{ $course->id }}</td>
                            <td>
                                <a href="{{ route('courses.show', $course) }}" class="fw-bold text-dark text-decoration-none">
                                    {{ $course->name }}
                                </a>
                            </td>
                            <td>
                                <span class="text-muted small">{{ Str::limit($course->description ?? 'No description provided.', 80) }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $course->students_count > 0 ? 'bg-primary-subtle text-primary' : 'bg-light text-muted border' }} rounded-pill px-3 py-1">
                                    <i class="bi bi-people-fill me-1"></i> {{ $course->students_count }} {{ Str::plural('student', $course->students_count) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $course->created_at->format('M d, Y') }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('courses.show', $course) }}" class="btn btn-light border" title="View details">
                                        <i class="bi bi-eye text-primary"></i>
                                    </a>
                                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-light border" title="Edit course">
                                        <i class="bi bi-pencil text-secondary"></i>
                                    </a>
                                    <button type="button" class="btn btn-light border text-danger" title="Delete course" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $course->id }}"
                                            {{ $course->students_count > 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $course->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered text-start">
                                        <div class="modal-content">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold">Delete Course</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                Are you sure you want to delete <strong>{{ $course->name }}</strong>? This action cannot be undone.
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline">
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
                                No courses found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($courses->hasPages())
        <div class="card-footer bg-white border-top p-3">
            {{ $courses->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
