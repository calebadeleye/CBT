<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function home()
    {
        return view('welcome');
    }

    public function login()
    {
        return view('login');
    }

    public function about()
    {
        return view('about');
    }

    public function leaderBoard()
    {
        return view('leaderboard');
    }

    public function contact()
    {
        return view('contact');
    }

    public function myBoard()
    {
        return view('myboard');
    }

    public function myScores()
    {
        return view('myscores');
    }

    public function terms()
    {
        return view('tc');
    }

    public function forgotPassword()
    {
        return view('forgot');
    }

    public function showQuiz()
    {
        return view('quiz');
    }

    public function showAdminLogin()
    {
        return view('admin');
    }

    public function showAdminBoard()
    {
        return view('adminboard');
    }

    public function showQuestions()
    {
        return view('questions');
    }

    public function addQuestion()
    {
        return view('addquestion');
    }

    public function addBank()
    {
        return view('addbank');
    }
    
}
