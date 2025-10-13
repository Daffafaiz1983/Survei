<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'status',
    ];

    /**
     * Get all images uploaded for this facility
     */
    public function images(): HasMany
    {
        return $this->hasMany(FacilityImage::class);
    }

    /**
     * Get pending images that need attention
     */
    public function pendingImages(): HasMany
    {
        return $this->hasMany(FacilityImage::class)->where('status', 'pending');
    }
}
