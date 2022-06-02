<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PegawaiRepository;

class HomeController extends Controller
{
    public function index()
    {
    	return redirect(route('login'));
    }

    public function home()
    {
        $repo = new PegawaiRepository;

        $totalPegawai = $repo->totalPegawai();
        $totalWilker = $repo->totalWilker();
        $totalGolongan = $repo->totalGolongan();
        $totalJabatan = $repo->totalJabatan();

    	return view('home')
            ->withTotalPegawai($totalPegawai)
            ->withTotalWilker($totalWilker)
            ->withTotalGolongan($totalGolongan)
            ->withTotalJabatan($totalJabatan);
    }
}
