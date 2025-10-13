<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalQuestions = Question::count();
        $totalAnswers = Answer::count();

        $respondents = Answer::distinct('user_id')->count('user_id');
        $responseRate = $totalUsers > 0 ? round(($respondents / $totalUsers) * 100, 1) : 0;

        // Respondents per role
        $respondentsMahasiswa = Answer::whereHas('user', function($q){ $q->where('role', 'mahasiswa'); })
            ->distinct('user_id')->count('user_id');
        $respondentsDosen = Answer::whereHas('user', function($q){ $q->where('role', 'dosen'); })
            ->distinct('user_id')->count('user_id');
        $respondentsStaff = Answer::whereHas('user', function($q){ $q->where('role', 'staff'); })
            ->distinct('user_id')->count('user_id');

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalQuestions',
            'totalAnswers',
            'responseRate',
            'respondents',
            'respondentsMahasiswa',
            'respondentsDosen',
            'respondentsStaff'
        ));
    }
}
