<?php

namespace App\Http\Controllers;

use App\DTOs\UserDTO;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function checkUserAvailable(Request $request)
    {
        $request->validate([
            'validationType' => ['required' , 'string' , Rule::in(['email' , 'userName'])],
            'validationValue' => ['required' , 'string' , 'max:255'],
        ]);

        return User::query()->where($request->input('validationType') , $request->input('validationValue'))->exists();
    }

    public function index()
    {
        /** @var User $user **/
        $user = Auth::user();

        if(!$user->hasRole('admin')){
            return response()->json(['you are not allowed.'] , Response::HTTP_FORBIDDEN);
        }

        return UserResource::collection(
            User::with(['media' , 'roles'])->where('clinic_id' , $user->clinic_id)->get()
        );

    }

    public function store(UserRequest $request)
    {
        $user = User::query()->create(UserDTO::fromArray($request->validated())->toArray());

        $user->assignRole($request->input('roles'));

        if($request->hasFile('avatar'))
            $this->handleMediaUpload($request->file('avatar') , $user);

        return UserResource::make($user->load(['media' , 'roles']));
    }


    public function update(UserRequest $request , User $user)
    {
        $user->update(UserDTO::fromArray($request->validated())->toArray());

        if($request->has('roles'))
             $user->syncRoles($request->input('roles'));

        if($request->hasFile('avatar'))
            $this->handleMediaUpdate($request->file('avatar') , $user);

        return UserResource::make($user->load(['media' , 'roles']));
    }

   public function changeStatus(User $user)
   {
        $user->update(['is_active' => $user->is_active]);

        return response()->noContent();
   }
}
