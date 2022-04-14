<?php  

namespace App\Repositories;

use App\Models\User;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Crypt;
use App\Http\Resources\User as UserApi;

ini_set('max_execution_time', 500);

class UserRepository extends BaseRepository
{
	protected $users; 

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'username' => 'required|string',
    		'password' => 'required|string|confirmed',
            'wilker' => 'required'
    	]);

    	$user = User::create([
    		'username' => $request->username,
    		'password' => bcrypt($request->password),
            'e_password' => Crypt::encrypt($request->password),
            'api_token' => Str::random(80),
    	]);

        $user->wilkers()->attach($request->wilker);

    	return back()->withSuccess('Berhasil tambah user baru');
    }

    public function update($request, $user)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'sometimes|string|confirmed|min:6',
            'wilker' => 'required'
        ]);

        if ($request->has('password')) {
            $user->update([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'e_password' => Crypt::encrypt($request->password)
            ]);
        } else {
            $user->update($request->only('username'));
        }

        $user->wilkers()->sync($request->wilker);

        return redirect(route('users.index'))->withSuccess('Berhasil edit user ' . $user->username);
    }

    public function import() 
    {
        Excel::import(new UsersImport, request()->file('file'));

        return back()->withSuccess('Berhasil tambah user baru');
    }

    public function show(Request $request)
    {
        $this->admin = $request->user()->hasRole('administrator');

        $this->users = User::whereId($request->user()->id);

        if ($this->admin) {
            $this->users = User::where('id', '!=', 1);
        } 

        $this->users = $this->users->get();

        $this->api = new UserApi($this->users);

        return $this->api;
    }

    public function fetch(User $user)
    {
        $this->username = $user->username;

        $this->password = Crypt::decrypt($user->e_password);

        try {

            $this->prepare();
            $this->login();

            if ($user->pegawai) {
               $user->pegawai()->update($this->getData('nip'));
            } else {
                $user->pegawai()->create($this->getData()); 
            }

            return back()->withSuccess('Berhasil update data pegawai');

        } catch (\Exception $e) {

            return back()->withWarning('Internal Server Error with message : ' . $e->getMessage());

        }
    }

    public function showTable(Request $request)
    {
       $this->show($request);

        return datatables($this->users)->addIndexColumn()
        ->addColumn('action', function($user) {

            if ($this->admin) {
                return '
                <a href="'. route('users.edit', $user->id) .'" class="btn btn-outline-primary btn-xs">
                    <i class="fas fa-pencil-alt"></i> Edit
                </a> 
                <a href="#" data-id="'. $user->id .'" class="btn btn-outline-danger btn-xs delete-user-btn">
                    <i class="fa fa-trash"></i> Delete
                </a>
                <a href= "'. route('users.fetch', $user->id) .'"  class="btn btn-outline-success btn-xs" onclick="addLoader(event)">
                    <i class="fa fa-wrench"></i> Update 
                </a>
                <a href= "'. route('users.roles', $user->id) .'"  class="btn btn-outline-info btn-xs">
                    <i class="fa fa-cog"></i> Role 
                </a>
                ';
            } 

            return '<a href= "'. route('users.fetch', $user->id) .'"  class="btn btn-success btn-xs" onclick="addLoader(event)">
                    <i class="fa fa-wrench"></i> Update 
                </a>';
        })
        ->make(true);
    }
}