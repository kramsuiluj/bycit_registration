<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function fullname()
    {
        return $this->lastname . ', ' . $this->firstname . ' ' . ($this->middle_initial != '' ? $this->middle_initial . '.' : '');
    }

    public function confirmed()
    {
        if ($this->confirmed == 'yes') {
            return '<span class="text-blue-500">Confirmed</span>';
        }

        if ($this->confirmed == 'no') {
            return '<span class="text-blue-500">Confirmed</span>';
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['school'] ?? false, fn($query, $school) =>
            $query->where(fn($query) =>
                $query->where('school_id', $school)
            )
        );

        $query->when($filters['confirmed'] ?? false, fn($query, $confirmed) =>
            $query->where(fn($query) =>
                $query->where('confirmed', $confirmed)
            )
        );
    }
}
