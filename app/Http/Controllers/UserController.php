<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
}
