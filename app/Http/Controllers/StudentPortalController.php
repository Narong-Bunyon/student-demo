<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentPortalController extends Controller
{
    /**
     * Display student portal dashboard with enrolled course details, cohort peers, and profile.
     */
    public function index(): View
    {
        $student = Auth::guard('student')->user();
        $student->load('course');

        // Get fellow classmates in the same course
        $classmates = $student->course
            ->students()
            ->where('id', '!=', $student->id)
            ->where('status', 'active')
            ->take(6)
            ->get();

        return view('student.portal', compact('student', 'classmates'));
    }
}
