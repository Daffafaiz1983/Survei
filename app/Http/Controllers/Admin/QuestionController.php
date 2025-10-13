<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua pertanyaan dengan pagination
        $questions = Question::orderBy('category')->paginate(10);
        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:text,multiple_choice,rating',
            'category' => 'nullable|string|max:100',
            'is_required' => 'boolean'
        ]);

        // Simpan pertanyaan baru
        Question::create([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'category' => $request->category,
            'is_required' => $request->has('is_required'),
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $question = Question::findOrFail($id);
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $question = Question::findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:text,multiple_choice,rating',
            'category' => 'nullable|string|max:100',
            'is_required' => 'boolean'
        ]);

        $question = Question::findOrFail($id);
        
        $question->update([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'category' => $request->category,
            'is_required' => $request->has('is_required'),
        ]);

        return redirect()->route('admin.questions.index')->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        
        return redirect()->route('admin.questions.index')->with('success', 'Pertanyaan berhasil dihapus.');
    }
}
