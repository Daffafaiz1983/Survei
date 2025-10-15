<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function index()
    {
        // Ambil hanya pertanyaan yang aktif dan BELUM dijawab oleh user saat ini
        $questions = Question::where('is_active', true)
            ->whereDoesntHave('answers', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        // Jika tidak ada pertanyaan tersisa untuk dijawab, anggap sudah submit
        $hasSubmitted = $questions->count() === 0;

        return view('dashboard', compact('questions', 'hasSubmitted'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'answers' => 'required|array'
        ]);

        foreach ($request->answers as $question_id => $answer_text) {
            Answer::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'question_id' => $question_id
                ],
                [
                    'answer_text' => $answer_text
                ]
            );
        }

        return redirect()->route('dashboard')->with('success', 'Survei berhasil disimpan!');
    }
}