@extends('layouts.app')

@section('title', 'Student Profile: ' . $student->full_name)
@section('page-title', 'Student Profile')

@section('content')
<div class="row g-4 justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card card-custom">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-4 border-bottom gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="stat-icon-box bg-primary-subtle text-primary rounded-circle" style="width: 64px; height: 64px; font-size: 1.75rem;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h4 class="fw-bold mb-0 text-dark">{{ $student->full_name }}</h4>
                                @if($student->status === 'active')
                                    <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                @endif
                            </div>
                            <span class="badge bg-light text-muted border font-monospace">{{ $student->student_code }}</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-light border">
                            <i class="bi bi-arrow-left me-1"></i> All Students
                        </a>
                    </div>
                </div>

                <div class="row g-4 mt-2">
                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small fw-semibold text-uppercase">Academic Program</span>
                            <div class="mt-2">
                                <h6 class="fw-bold text-dark mb-1">{{ $student->course->name }}</h6>
                                <p class="text-muted small mb-2">{{ $student->course->description ?? 'No description.' }}</p>
                                <a href="{{ route('courses.show', $student->course_id) }}" class="small text-decoration-none text-primary fw-medium">
                                    View course cohort &rarr;
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted small fw-semibold text-uppercase">Enrollment Details</span>
                            <div class="mt-2 small">
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Registered On:</span>
                                    <span class="text-dark fw-medium">{{ $student->created_at->format('F d, Y') }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1 border-bottom">
                                    <span class="text-muted">Last Updated:</span>
                                    <span class="text-dark fw-medium">{{ $student->updated_at->format('F d, Y H:i') }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted">System ID:</span>
                                    <span class="text-dark font-monospace">#{{ $student->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <h6 class="fw-bold text-dark mb-3">Contact & Demographic Details</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light w-25 text-muted small">Email Address</th>
                                        <td><a href="mailto:{{ $student->email }}" class="text-decoration-none">{{ $student->email }}</a></td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small">Phone Number</th>
                                        <td>{{ $student->phone ?? 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small">Date of Birth</th>
                                        <td>{{ $student->date_of_birth ? $student->date_of_birth->format('F d, Y') : 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light text-muted small">Gender</th>
                                        <td>{{ $student->gender ?? 'Not specified' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                        <i class="bi bi-trash me-1"></i> Delete Student
                    </button>
                </div>

                <!-- Modal Delete -->
                <div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered text-start">
                        <div class="modal-content">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body py-3">
                                Are you sure you want to remove <strong>{{ $student->full_name }}</strong> from the database?
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('students.destroy', $student) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
