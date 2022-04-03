<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterPegawai as Pegawai;
use App\Repositories\PegawaiRepository as Repository;

ini_set('max_execution_time', 500);

class PegawaiController extends Controller
{
    protected $pegawai;

    public function __construct(Repository $pegawai)
    {
        $this->pegawai = $pegawai;
    }

    public function index()
    {
        return view('pegawai.index');
    }

    public function show($nip)
    {
    	return $this->pegawai->show($request);
    }

    public function detailPegawai($id = 3)
    {   
        $user = \App\Models\User::find($id);

        return response()->json($user);
    }

    public function detail($nip = null)
    {
        if (auth()->user()->hasRole('administrator')) {
            return $this->index();
        }

        $nip = $nip ?? auth()->user()->pegawai->nip;

        $pegawai = $this->pegawai->detail($nip);

        return view('pegawai.show')->withPegawai($pegawai);
    }

    public function showTable(Request $request)
    {
       return $this->pegawai->showTable($request);
    }

    public function fetchSimAsnData(Pegawai $pegawai)
    {
        return $this->pegawai->fetch($pegawai);
    }

    public function fetchAllSimAsnData(Request $request)
    {
        return $this->pegawai->fetchAll($request);
    }
}
