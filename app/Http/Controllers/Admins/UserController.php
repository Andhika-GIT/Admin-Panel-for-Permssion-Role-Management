<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['role:super-admin|admin|moderator|developer']);
    }
    public function index()
    {
        return Inertia::render('Admins/Users/Index', [
            'users' => User::where('is_admin', 0)->latest()->paginate(7),
            'roles' => Role::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => 0,
            'password' => Hash::make('passsword')
        ]);

        $role = Role::where('id', 5)->first();
        $user->syncRoles($role);
        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // dd($request->roles[0]);

        $this->validate($request, [
            'name' => ['required', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:50'],
        ]);
        if ($request->roles[0] === null) {
            return back()->withErrors(['roles' => 'The role field is required']);
        }
        if ($request->roles[0]['id'] != 5) { // if the id from request->roles is not 5 ( role that has id with 5 is the user role),  then the user is become admin
            $adminRole = Role::where('id', $request->roles[0]['id'])->first(); // get the id from $request->roles
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'is_admin' => 1, // make the user admin
            ]);
            $user->syncRoles($adminRole);
            return redirect()->route('admin.users.index');
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }
}
