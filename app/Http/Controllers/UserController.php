<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function index()
    {
        //
        $datas = User::orderBy('name')->get();

        return view('user.listing', ['datas' => $datas]);
    }

    public function create()
    {
        //
        return view('user.add_user');
    }

    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user_data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status' => 'ACTIVE',
        ];

        if($request->hasFile('image')) {

            $image = $request->file('image');
            $folder_path = 'User';
            $path = $image->store($folder_path);

            $user_data['image'] = $path;

        }

        $user = User::create($user_data);

        return response()->json(['status'=>"success"], 200);

    }

    public function show(User $user)
    {
        //
        $datas = User::where('id', $user->id)->first();

        return view('user.edit_user', ['datas' => $datas]);
    }

    public function edit(User $user)
    {
        //
    }

    public function update(Request $request, User $user)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'status' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user_data = [
            'name'=> $request->name,
            'email'=> $request->email,
            'role'=> $request->role,
            'status'=> $request->status,
        ];

        if($request->hasFile('image')) {

            // Delete old image if exists
            if ($user->image) {
                if (Storage::exists($user->image)) {
                    Storage::delete($user->image);
                }
            }

            $image = $request->file('image');
            $folder_path = 'User';
            $path = $image->store($folder_path);

            $user_data['image'] = $path;

        }

        $user->fill($user_data);
        $user->save();

        return response()->json(['status'=>"success"], 200);
    }

    public function destroy(User $user)
    {
        //
        $user->delete();
        return response()->json(['status'=>"success"], 200);
    }

    public function delete_image(User $user)
    {
        //
        if ($user->image) {
            if (Storage::exists($user->image)) {
                Storage::delete($user->image);
            }
            $user->image = null;
            $user->save();
        }

        return response()->json(['status'=>"success"], 200);
    }

    public function change_password(Request $request, User $user)
    {
        //
        $validatedData = $request->validate([
            'new_password' => 'required|string',
        ]);

        $user['password'] = bcrypt($request->new_password);
        $user->save();

        return response()->json(['status'=>"success"], 200);

    }

    public function profile(User $user)
    {
        //
        $datas = User::where('id', $user->id)->first();

        return view('profile', ['datas' => $datas]);
    }

    public function register(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);

        $user_data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'USER',
            'status' => 'ACTIVE',
        ];

        User::create($user_data);

        return response()->json(['status'=>"success"], 200);

    }

}
