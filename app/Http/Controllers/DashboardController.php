<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display a dashboard with statistics and recent activity.
     */
    public function index(): View
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $activeStudents = Student::where('status', 'active')->count();
        $inactiveStudents = Student::where('status', 'inactive')->count();

        $recentStudents = Student::with('course')
            ->latest()
            ->take(5)
            ->get();

        $coursesWithCount = Course::withCount('students')
            ->orderByDesc('students_count')
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalStudents',
            'totalCourses',
            'activeStudents',
            'inactiveStudents',
            'recentStudents',
            'coursesWithCount'
        ));
    }
}
