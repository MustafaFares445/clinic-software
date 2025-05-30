<?php

namespace App\Models;

use App\Models\Clinic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Treatment extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'color',
        'clinic_id'
    ];

    protected static function booted(): void
    {
        /** @var User $user */
        $user = Auth::user();

        if (Auth::check() && !$user->hasRole('super Admin')) {
            $clinicId = request()->input('clinicId') ?? $user->clinic_id;
            static::query()->whereRelation('clinic', 'id', $clinicId);
        }
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
}
