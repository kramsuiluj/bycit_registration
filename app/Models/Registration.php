<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function others()
    {
        return $this->hasOne(Other::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function size()
    {
        return $this->belongsTo(Sizes::class, 'tshirt');
    }

    public function fullname()
    {
        return $this->lastname . ', ' . $this->firstname . ' ' . ($this->middle_initial != '' ? $this->middle_initial . '.' : '');
    }

    public function paid()
    {
        return $this->paid === 'yes';
    }

    public function isStudent(): bool
    {
        return $this->type === 'Student';
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(
            $filters['school'] ?? false,
            fn ($query, $school) =>
            $query->where(
                fn ($query) =>
                $query->where('school_id', $school)
            )
        );

        $query->when(
            $filters['paid'] ?? false,
            fn ($query, $paid) =>
            $query->where(
                fn ($query) =>
                $query->where('paid', $paid)
            )
        );

        $query->when(
            $filters['type'] ?? false,
            fn ($query, $type) =>
            $query->where(
                fn ($query) =>
                $query->where('type', $type)
            )
        );

        $query->when(
            $filters['sort'] ?? false,
            fn ($query, $paid) =>
            $query->where(
                fn ($query) =>
                $query->orderBy('type')
            )
        );

        $query->when($filters['search'] ?? false, fn ($query, $search) =>
            $query->where(fn ($query) =>
                $query->where('lastname', 'like', '%' . $search . '%')
                    ->orWhere('firstname', 'like', '%' . $search . '%')
                    ->orWhere('nickname', 'like', '%' . $search . '%')
            )
        );
    }
}
