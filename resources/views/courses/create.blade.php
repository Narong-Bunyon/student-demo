@extends('layouts.app')

@section('title', 'Create Course')
@section('page-title', 'Add New Course')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-custom">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <a href="{{ route('courses.index') }}" class="btn btn-sm btn-light border">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h5 class="fw-bold mb-0 text-dark">Create New Course</h5>
                </div>
                <p class="text-muted small ps-4 ms-2">Enter the information below to add a new course</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('courses.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-secondary">Course Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="e.g. Computer Science" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold text-secondary">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Brief outline of course syllabus or objectives...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-check-lg me-1"></i> Save Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
