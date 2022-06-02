<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wilker;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Repositories\UserRepository as Repository;

ini_set('max_execution_time', 500);

class UsersController extends Controller
{
    protected $users;    

    public function __construct(Repository $users)
    {
        $this->users = $users;
    }

    public function index()
    {
    	return view('users.index');
    }

    public function create()
    {
    	return view('users.create')->withWilkers(Wilker::all());
    }

    public function createBulk()
    {
    	return view('users.create_bulk');
    }

    public function store(Request $request)
    {
        return $this->users->store($request);
    }

    public function import() 
    {
        return $this->users->import();    
    }

    public function edit(User $user)
    {
        $user->load('wilkers');

        return view('users.edit')->withUser($user)->withWilkers(Wilker::all());
    }

    public function update(Request $request, User $user)
    {
        return $this->users->update($request, $user); 
    }

    public function show(Request $request)
    {
        return $this->users->show($request);  
    }

    public function fetch(User $user)
    {
        return $this->users->fetch($user);
    }

    public function delete(User $user)
    {
        $user->wilkers()->detach();
        $user->delete();

        return back()->withSuccess('Berhasil delete user');
    }

    public function showTable(Request $request)
    {
       return $this->users->showTable($request);
    }

    public function roles(User $user)
    {
        $roles = Role::all();

        return view('users.roles')->withUser($user)->withRoles($roles);
    }

    public function attachRoles(Request $request, User $user)
    {
        $user->syncRoles($request->roles);

        return back()->withSuccess('Roles added');
    }

    public function showRoles(Request $request)
    {
        return response()->json($request->user()->roles->pluck('name'));
    }

    public function forAuth(Request $request)
    {
        return $this->users->forAuth($request);
    }

}
