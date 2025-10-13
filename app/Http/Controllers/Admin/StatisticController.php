<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use Illuminate\Support\Facades\DB;
use App\Models\Answer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StatisticController extends Controller
{
    public function index()
    {
        $role = request('role'); // 'mahasiswa' | 'dosen' | 'staff' | null

        $statistics = Question::query()
            ->withCount([
                'answers as answers_count' => function ($q) use ($role) {
                    if ($role) {
                        $q->whereHas('user', function ($uq) use ($role) {
                            $uq->where('role', $role);
                        });
                    }
                }
            ])
            ->with(['answers' => function ($query) use ($role) {
                if ($role) {
                    $query->whereHas('user', function ($uq) use ($role) {
                        $uq->where('role', $role);
                    });
                }
                $query->select('question_id', 'answer_text', DB::raw('count(*) as total'))
                      ->groupBy('question_id', 'answer_text');
            }])
            ->get();

        return view('admin.statistics.index', compact('statistics', 'role'));
    }

    /**
     * Export semua jawaban mentah sebagai CSV.
     */
    public function exportRaw(): StreamedResponse
    {
        $role = request('role');
        $suffix = $role ? ('_' . $role) : '';
        $fileName = 'survei_raw' . $suffix . '_' . now()->format('Ymd_His') . '.csv';

        $callback = function () {
            $handle = fopen('php://output', 'w');
            // Header
            fputcsv($handle, ['Answer ID', 'User ID', 'User Role', 'Question ID', 'Question', 'Type', 'Category', 'Answer Text', 'Created At']);

            $roleFilter = request('role');
            $query = Answer::query()
                ->with(['question:id,question_text,question_type,category', 'user:id,role'])
                ->orderBy('id');

            if ($roleFilter) {
                $query->whereHas('user', function ($uq) use ($roleFilter) {
                    $uq->where('role', $roleFilter);
                });
            }

            $query->chunk(500, function ($answers) use ($handle) {
                foreach ($answers as $answer) {
                    fputcsv($handle, [
                        $answer->id,
                        $answer->user_id,
                        $answer->user?->role,
                        $answer->question_id,
                        optional($answer->question)->question_text,
                        optional($answer->question)->question_type,
                        optional($answer->question)->category,
                        $answer->answer_text,
                        $answer->created_at,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Export ringkasan agregat per pertanyaan sebagai CSV.
     */
    public function exportSummary(): StreamedResponse
    {
        $role = request('role');
        $suffix = $role ? ('_' . $role) : '';
        $fileName = 'survei_ringkasan' . $suffix . '_' . now()->format('Ymd_His') . '.csv';

        $stats = Question::query()
            ->withCount([
                'answers as answers_count' => function ($q) use ($role) {
                    if ($role) {
                        $q->whereHas('user', function ($uq) use ($role) {
                            $uq->where('role', $role);
                        });
                    }
                }
            ])
            ->with(['answers' => function ($query) use ($role) {
                if ($role) {
                    $query->whereHas('user', function ($uq) use ($role) {
                        $uq->where('role', $role);
                    });
                }
                $query->select('question_id', 'answer_text', DB::raw('count(*) as total'))
                      ->groupBy('question_id', 'answer_text');
            }])->get();

        $callback = function () use ($stats, $role) {
            $handle = fopen('php://output', 'w');
            // Header
            fputcsv($handle, ['Question ID', 'Question', 'Type', 'Category', 'Respondents', 'Choice/Value', 'Total', 'Filter Role']);

            foreach ($stats as $q) {
                if ($q->answers->isEmpty()) {
                    fputcsv($handle, [$q->id, $q->question_text, $q->question_type, $q->category, $q->answers_count, '-', 0, $role ?: 'all']);
                    continue;
                }
                foreach ($q->answers as $a) {
                    fputcsv($handle, [
                        $q->id,
                        $q->question_text,
                        $q->question_type,
                        $q->category,
                        $q->answers_count,
                        $a->answer_text,
                        $a->total,
                        $role ?: 'all',
                    ]);
                }
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
