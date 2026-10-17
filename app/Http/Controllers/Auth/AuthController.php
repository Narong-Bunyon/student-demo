<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        if (Auth::guard('student')->check()) {
            return redirect()->route('student.portal');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication (admin first, then student).
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // 1. Check if user is an Administrator
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Welcome back, ' . Auth::guard('web')->user()->name . '!');
        }

        // 2. Check if user is a Student
        if (Auth::guard('student')->attempt($credentials, $remember)) {
            $student = Auth::guard('student')->user();

            if ($student->status !== 'active') {
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Your student account is currently inactive. Please contact administration.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('student.portal'))
                ->with('success', 'Welcome, ' . $student->first_name . '!');
        }

        // 3. Invalid credentials
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show the registration form for new students.
     */
    public function showRegisterForm(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }
        if (Auth::guard('student')->check()) {
            return redirect()->route('student.portal');
        }

        $courses = \App\Models\Course::orderBy('name')->get();

        return view('auth.register', compact('courses'));
    }

    /**
     * Handle registration of a new student.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name'  => ['required', 'string', 'max:50'],
            'email'      => ['required', 'email', 'max:255', 'unique:students,email'],
            'course_id'  => ['nullable', 'exists:courses,id'],
            'password'   => ['required', 'confirmed', 'min:8'],
        ]);

        // Auto-assign default course if not selected
        $courseId = $validated['course_id'] ?? \App\Models\Course::value('id');

        // Generate a unique student code: e.g. STU-2026-XXXX
        $year = date('Y');
        $lastStudentId = (int) Student::max('id') + 1;
        $studentCode = sprintf('STU-%s-%04d', $year, $lastStudentId);

        // Ensure uniqueness
        while (Student::where('student_code', $studentCode)->exists()) {
            $lastStudentId++;
            $studentCode = sprintf('STU-%s-%04d', $year, $lastStudentId);
        }

        $student = Student::create([
            'student_code' => $studentCode,
            'first_name'   => $validated['first_name'],
            'last_name'    => $validated['last_name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'course_id'    => $courseId,
            'status'       => 'active',
            'role'         => 'student',
        ]);

        Auth::guard('student')->login($student);

        $request->session()->regenerate();

        return redirect()->intended(route('student.portal'))
            ->with('success', 'Welcome, ' . $validated['first_name'] . '!');
    }

    /**
     * Log out the authenticated user or student.
     */
    public function logout(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been successfully logged out.');
    }
}
