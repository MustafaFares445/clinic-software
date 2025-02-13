<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClinicRequest;
use App\Http\Resources\ClinicResource;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
   public function get(): ClinicResource
   {
      return ClinicResource::make(Auth::user()->clinic);
   }

   public function addClinicSubscription(ClinicRequest $request): Response
   {
       Clinic::query()->create($request->clinicValidated());
       $user =  User::query()->create($request->userValidated());

       $user->assignRole('admin');
       return response()->noContent();
   }
   public function update(ClinicRequest $request): ClinicResource
   {
        $clinic = Auth::user()->clinic;
        $clinic->update($request->validated());

        return ClinicResource::make($clinic);
   }

  public function changeStatus(): Response
  {
      $clinic = Auth::user()->clinic;
      $clinic->update(['is_banned' => true]);

      User::query()->where(['clinic_id' => $clinic->id])->update(['is_banned' => true]);

      return response()->noContent();
  }
}
