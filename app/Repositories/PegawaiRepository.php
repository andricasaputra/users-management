<?php  

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\MasterPegawai as Pegawai;
use App\Models\Wilker;
use Illuminate\Support\Facades\Crypt;
use App\Http\Resources\User as UserApi;

class PegawaiRepository extends BaseRepository
{
    protected $pegawai;

	public function __construct()
	{
		parent::__construct();
	}

    public function show(Request $request)
    {
        $this->admin = $request->user()->hasRole('administrator');

        $this->pegawai = User::whereId($request->user()->id);

        if ($this->admin) {
            $this->pegawai = User::where('id', '!=', 1);
        } 

        $this->pegawai = $this->pegawai->has('pegawai')->get();

        $this->api = new UserApi($this->pegawai);

        return $this->api;
    }

    public function showTable(Request $request)
    {
       $this->show($request);

        return datatables($this->pegawai)->addIndexColumn()
        ->addColumn('action', function($user) {

            return '
            <a href= "'. route('pegawai.update', $user->pegawai['id']) .'"  class="btn btn-success btn-xs" id="deleteUser">
                <i class="fa fa-eye"></i> Update 
            </a>';
            
        })
        ->make(true);
    }

    public function detail($nip)
    {
    	return Pegawai::whereNip($nip)->first();
    }

    public function fetch(Pegawai $pegawai)
    {
        $this->username = $pegawai->user->username;
        $this->password = Crypt::decrypt($pegawai->user->e_password);

    	try {

            $this->prepare();
    		$this->login();

            $pegawai->update($this->getData());

            return back()->withSuccess('Berhasil update data pegawai');

        } catch (\Exception $e) {

        	return back()->withWarning('Internal Server Error with message : ' . $e->getMessage());

        }
    }

    public function fetchAll(Request $request)
    {
        $request->validate([
            'dari' => 'required',
            'jumlah' => 'required'
        ]);

    	$users = User::where('id', '!=', 1)->get();

        if ($users->count() == 0) {
           return response()->json([
                'alert' => 'alert-danger',
                'message' => 'Data pegawai masih kosong, silahkan tambahkan user pegawai terlebih dahulu'
            ]);
        } 

        $users = $users->slice(($request->dari - 1), $request->jumlah);
    			
        $counter  = 0;

    	try {

    		$this->prepare();

            foreach ($users as $user) {

            	$this->username = $user->username;
        		$this->password = Crypt::decrypt($user->e_password);

                $this->login();

                $pegawai = Pegawai::where('user_id', $user->id)->first();

                if ($pegawai) {
                   $pegawai->update($this->getData('nip'));
                } else {
                    $data = $this->getData();
                    $data += ['user_id' => $user->id];
                    Pegawai::create($data); 
                }

                $counter++;
            }

            return response()->json([
                'alert' => 'alert-success',
                'message' => "Berhasil update $counter data pegawai"
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'alert' => 'alert-danger',
                'message' => 'Internal Server Error with message : ' . $e->getMessage()
            ]);
        }
    }

    public function totalPegawai()
    {
        return Pegawai::get()->filter(function($p){
            return $p->nama != 'administrator';
        })->count();
    }

    public function totalWilker()
    {
        return Wilker::get()->count();
    }

    public function totalGolongan()
    {
        return Pegawai::selectRaw("count(gol_akhir) as total_golongan, gol_akhir as golongan")->where('gol_akhir', '!=' ,'-')->groupBy('gol_akhir')->get();
    }

    public function totalJabatan()
    {
        return Pegawai::selectRaw("count(jabatan) as total_jabatan, jabatan as jabatan")->where('jabatan', '!=' ,'-')->groupBy('jabatan')->get();
    }
}