<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormRequestUser;
use App\Models\Components;
use Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;

class UserController extends Controller
{
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function index(Request $request) {
        $search = $request->search;
        $findUser = $this->user->getUserSearchIndex(search: $search ?? '');

        return view('pages/users/paging', compact('findUser'));
    }

    public function delete(Request $request) {
        $id = $request->id;
        $SearchRegistry = User::find($id);
        $SearchRegistry->delete();
        return response()->json(['success' => true]);    
    }
    
    public function registerUser(FormRequestUser $request) {
        if ($request->method() == 'POST') {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            User::create($data);
            
            Toastr::success('User successfully added');

            return redirect()->route('users.index');
        }

        return view('pages.users.create');
    }

    public function updateUser(FormRequestUser $request, $id) {
        if ($request->method() == 'PUT') {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);

            $searchRegistry = User::find($id);
            $searchRegistry->update($data);

            return redirect()->route('users.index');
        }

        $findUser = User::where('id', '=', $id)->first();

        return view('pages.users.update', compact('findUser'));
    }
}
