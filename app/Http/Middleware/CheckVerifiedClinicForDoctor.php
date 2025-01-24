<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CheckVerifiedClinicForDoctor
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (ResponseAlias) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->validate([
            'clinicId' => ['nullable' , 'string' , Rule::exists('clinics' , 'id')]
        ]);

        // Validate clinic access for the authenticated user
        if ($request->has('clinicId') && Auth::user()->hasRole('doctor') && Auth::user()->doctorClinics()->where('clinic_id', $request->input('clinicId'))->doesntExist()) {
            return response()->json(['error' => 'You are not allowed.'], ResponseAlias::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
