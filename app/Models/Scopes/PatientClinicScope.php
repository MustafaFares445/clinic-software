<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class PatientClinicScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $query, Model $model)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (Auth::check() && !$user->hasRole('super Admin')) {
            $clinicId = request()->input('clinicId') ?? $user->clinic_id;
            $query->whereRelation('clinic', 'id', $clinicId);
        }

        // if (Auth::check() && $user->hasAllRoles('doctor')) {
        //     $query->where(function ($q) use ($user) {
        //         $q->whereRelation('records.doctors', 'doctor_id', $user->id)
        //           ->orWhereRelation('reservations', 'doctor_id', $user->id);
        //     });
        // }
    }
}
