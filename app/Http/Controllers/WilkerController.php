<?php

namespace App\Http\Controllers;

use App\MasterPegawai as Pegawai;
use App\Models\Wilker;
use Illuminate\Http\Request;

class WilkerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
    	return view('wilkers.index')->withWilkers(Wilker::all());
    }

    public function create()
    {
        $users = Pegawai::get()->map(function($user){
            return [
                'id' => $user->id,
                'nama' => $user->nama,
                'nip' => $user->nip
            ];
        });

    	return view('wilkers.create')->withUsers($users);
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'nama_wilker' => 'required',
            'pj_id' => 'required'
    	]);

    	Wilker::create($request->only(['nama_wilker', 'pj_id']));

    	return redirect(route('wilkers.index'))->withSuccess('Berhasil Tambah Wilker');
    }

    public function edit(Wilker $wilker)
    {
        $users = Pegawai::get()->map(function($user){
            return [
                'id' => $user->id,
                'nama' => $user->nama,
                'nip' => $user->nip
            ];
        });

    	return view('wilkers.edit')->withWilker($wilker)->withUsers($users);
    }

    public function update(Request $request, Wilker $wilker)
    {
    	$request->validate([
    		'nama_wilker' => 'required',
            'pj_id' => 'required'
    	]);

    	$wilker->update($request->only(['nama_wilker', 'pj_id']));

    	return redirect(route('wilkers.index'))->withSuccess('Berhasil Edit Nama Wilker');
    }

    public function destroy(Wilker $wilker)
    {
    	$wilker->delete();

    	return response()->json([
            'message' => 'Berhasil Hapus Wilker',
            'redirect' => route('wilkers.index') 
        ], 200);
    }
}
