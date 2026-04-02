<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmprahanReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'submitted_by',
        'image_path',
        'report_time',
        'shift',
        'officer_name',
        'male_patient_count',
        'female_patient_count',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }
}
