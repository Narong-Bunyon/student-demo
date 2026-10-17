<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Student Management System</title>

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-wrapper">

    <div class="login-card shadow-lg">
        <!-- Brand Header -->
        <div class="bg-light p-4 text-center border-bottom">
            <div class="d-inline-flex align-items-center justify-content-center p-3 rounded-4 shadow-sm mb-3" style="background: linear-gradient(135deg, #4f46e5, #6366f1); color: white; width: 64px; height: 64px;">
                <i class="bi bi-person-plus-fill fs-2"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1">Create Account</h4>
            <p class="text-muted small mb-0">Register as a new student</p>
        </div>

        <!-- Registration Form -->
        <div class="p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show small border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show small border-0 shadow-sm" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <!-- First Name -->
                <div class="mb-3">
                    <label for="first_name" class="form-label small fw-semibold text-secondary">First Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0 @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required autofocus placeholder="Enter first name">
                    </div>
                </div>

                <!-- Last Name -->
                <div class="mb-3">
                    <label for="last_name" class="form-label small fw-semibold text-secondary">Last Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0 @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required placeholder="Enter last name">
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                    </div>
                </div>

                <!-- Course / Program Selection -->
                @if(isset($courses) && $courses->count() > 0)
                <div class="mb-3">
                    <label for="course_id" class="form-label small fw-semibold text-secondary">Course / Program</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-book"></i></span>
                        <select class="form-select border-start-0 @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                            <option value="" disabled {{ old('course_id') ? '' : 'selected' }}>Select your academic program</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimum 8 characters">
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Confirm Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" required placeholder="Re-enter password">
                    </div>
                </div>

                <button type="submit" class="btn btn-brand w-100 py-2.5">
                    <i class="bi bi-person-plus me-1"></i> Register
                </button>
            </form>

            <hr class="my-4">

            <p class="text-center text-muted small mb-0">
                Already have an account? <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Sign In</a>
            </p>
        </div>
    </div>

</body>
</html>
