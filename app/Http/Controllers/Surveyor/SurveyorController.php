<?php

namespace App\Http\Controllers\Surveyor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyorController extends Controller
{
    public function index()
    {
        return view('surveyor.dashboard');
    }
}
