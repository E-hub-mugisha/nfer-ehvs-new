<?php

namespace App\Http\Controllers\Government;

use App\Http\Controllers\Controller;
use App\Models\Employer;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function index()
    {
        $employers = Employer::latest()->paginate(10);

        return view(
            'government.employers.index',
            compact('employers')
        );
    }

    public function show($id)
    {
        $employer = Employer::with([
            'employmentRecords'
        ])->findOrFail($id);

        return view(
            'government.employers.show',
            compact('employer')
        );
    }
}
