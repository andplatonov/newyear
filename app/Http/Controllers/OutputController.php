<?php

namespace App\Http\Controllers;

use App\User;
use App\Interviewer;
use App\Question;
use App\Http\Controllers\Controller;

class OutputController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function show()
    {
        return view('output', ['interviewers' => Interviewer::all(), 'questions' => Question::all()]);
    }
}