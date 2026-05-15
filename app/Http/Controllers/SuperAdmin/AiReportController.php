<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiReportController extends Controller
{
    public function generate(Request $request)
    {
        return back()->with('success', 'AI report generated successfully.');
    }
}