<?php

namespace App\Models\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class RecordScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        $user = Auth::user();

        if (Auth::check() && !$user->hasRole('super Admin')) {
            $builder->whereRelation('clinic', 'id', request()->input('clinicId') ?? $user->clinic_id);
        }

        if (Auth::check() && $user->hasAllRoles('doctor')) {
            $builder->whereRelation('doctors', 'doctor_id', Auth::id());
        }
    }
}

