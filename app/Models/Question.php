<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'question_text',
        'question_type',
        'category',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean'
    ];

    /**
     * Answers submitted for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
