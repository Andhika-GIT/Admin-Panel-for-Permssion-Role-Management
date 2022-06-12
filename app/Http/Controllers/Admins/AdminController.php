<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware(['role:super-admin|admin']);
    }
    public function index()
    {
        return Inertia::render('Admins/Admins/Index', [
            'admins' => User::where('is_admin', 1)->latest()->paginate(5),
            'roles' => Role::where('id', '!=', 1)->get()
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
        //
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

        if ($request->roles === null) {
            return back()->withErrors(['roles' => 'The role field is required']);
        }
        if ($request->roles['id'] != 5) { // if the id from request->roles is not 5 ( role that has id with 5 is the user role),  then the user is become admin
            $adminRole = Role::where('id', $request->roles['id'])->first(); // get the id from $request->roles
            $user->syncRoles($adminRole);
            return redirect()->route('admin.admins.index');
        } else {
            // if the $request->roles id is not 5, then the user will not become admin
            $userRole = Role::where('id', 5)->first(); //get the role where id is 5
            $user->update(['is_admin' => 0]); // make the user not admin
            $user->syncRoles($userRole);
            return redirect()->route('admin.admins.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
