<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Proyek;
use App\Models\Catatan\TransaksiProyek;
use App\Models\Catatan\Anggaran;
use App\Models\AkunTransaksiProyek;
use App\Models\Manajemen;
use Illuminate\Support\Facades\DB;
use App\Models\Catatan\TransaksiKantor;
use App\Models\Perusahaan;
use DateTime;

class LaporanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function pageLabaRugi($id_proyek = null, $date_range = null){
        // dd($id_proyek);
        $start_date = null;
        $end_date = null;
        if(!(is_null($date_range)) && $date_range != 'all')
        {
            $separated = explode(' - ', $date_range);
            $start_date = Carbon::CreateFromFormat('d-m-Y', $separated[0])->startOfDay();
            $end_date = Carbon::CreateFromFormat('d-m-Y', $separated[1])->endOfDay();

            $date_range = str_replace('-', '/', $date_range);
            $date_range = str_replace(' / ', ' - ', $date_range);
        }
        else $date_range = null;

        if(Auth::user()->role == 4)
        {
            $proyeks = Proyek::where('id_pemilik', Auth::user()->id)->get();
        }
        else
        {
            $proyeks = Proyek::where('id_perusahaan', Auth::user()->id_perusahaan)->get();
        }

        $curr_proyek = null;
        if(!(is_null($id_proyek)) && $id_proyek != 'all')
        {
            $curr_proyek = Proyek::where('id_perusahaan', Auth::user()->id_perusahaan)
                                ->where('id', $id_proyek)
                                ->first();
        }

        $pendapatans = AkunTransaksiProyek::where('id_perusahaan', Auth::user()->id_perusahaan)
                    ->where('jenis', 'Masuk')
                    ->get();
        // $biayas = AkunTransaksiProyek::select('manajemen.*')
        //         ->where('akun_transaksi_proyeks.id_perusahaan', Auth::user()->id_perusahaan)
        //         ->where('akun_transaksi_proyeks.jenis', 'Keluar')
        //         ->join("manajemen","manajemen.id","akun_transaksi_proyeks.idManajemen")
        //         ->groupBy("manajemen.id")
        //         ->get();
        $biayas = AkunTransaksiProyek::select('manajemen.*')
                ->where('akun_transaksi_proyeks.id_perusahaan', Auth::user()->id_perusahaan)
                ->where('akun_transaksi_proyeks.jenis', 'Keluar')
                ->join("manajemen","manajemen.id","akun_transaksi_proyeks.idManajemen")
                ->groupBy("manajemen.id")
                ->get();
        $perusahaan = Perusahaan::with('user')->get()->where('kode_perusahaan', '=', Auth::user()->kode_perusahaan)->first();
        // dd($anggarans, $realisasis);
        return view('laporan/laba_rugi', compact('proyeks', 'curr_proyek',
        'pendapatans', 'biayas', 'start_date', 'end_date', 'date_range', 'perusahaan'));
    }

    public function pageLabaRugiKantor($date_range = null){
        if(!(is_null($date_range)))
        {
            $separated = explode(' - ', $date_range);
            $start = Carbon::CreateFromFormat('d-m-Y', $separated[0])->startOfDay();
            $end = Carbon::CreateFromFormat('d-m-Y', $separated[1])->endOfDay();
            $sum_per_akuns = TransaksiKantor::with('akun_tr_kantor')
            ->select('id_akun_tr_kantor', DB::raw('SUM(jumlah) as total_jumlah'))
            ->where('id_perusahaan', '=', Auth::user()->id_perusahaan)
            ->whereBetween('tgl_transaksi', [$start, $end])
            ->groupBy('id_akun_tr_kantor')
            ->get();

            $date_range = str_replace('-', '/', $date_range);
            $date_range = str_replace(' / ', ' - ', $date_range);
        }
        else
        {
            $sum_per_akuns = TransaksiKantor::with('akun_tr_kantor')
                               ->select('id_akun_tr_kantor', DB::raw('SUM(jumlah) as total_jumlah'))
                               ->where('id_perusahaan', '=', Auth::user()->id_perusahaan)
                               ->groupBy('id_akun_tr_kantor')
                               ->get();
        }
        $perusahaan = Perusahaan::with('user')->get()->where('kode_perusahaan', '=', Auth::user()->kode_perusahaan)->first();
        return view('laporan/laba_rugi_kantor', [
            'date_range' => $date_range,
            'perusahaan' => $perusahaan,
            'sum_per_akuns' => $sum_per_akuns,
        ]);
    }

    public function pageLabaRugiProyek($id_proyek = null, $date_range = null){
        // dd($id_proyek);
        $start_date = null;
        $end_date = null;
        if(!(is_null($date_range)) && $date_range != 'all')
        {
            $separated = explode(' - ', $date_range);
            $start_date = Carbon::CreateFromFormat('d-m-Y', $separated[0])->startOfDay();
            $end_date = Carbon::CreateFromFormat('d-m-Y', $separated[1])->endOfDay();

            $date_range = str_replace('-', '/', $date_range);
            $date_range = str_replace(' / ', ' - ', $date_range);
        }
        else $date_range = null;

        if(Auth::user()->role == 4)
        {
            $proyeks = Proyek::where('id_pemilik', Auth::user()->id)->get();
        }
        else
        {
            $proyeks = Proyek::where('id_perusahaan', Auth::user()->id_perusahaan)->get();
        }

        $curr_proyek = null;
        if(!(is_null($id_proyek)) && $id_proyek != 'all')
        {
            $curr_proyek = Proyek::where('id_perusahaan', Auth::user()->id_perusahaan)
                                ->where('id', $id_proyek)
                                ->first();
        }

        $pendapatans = AkunTransaksiProyek::where('id_perusahaan', Auth::user()->id_perusahaan)
                    ->where('jenis', 'Masuk')
                    ->get();
        $biayas = AkunTransaksiProyek::select('manajemen.*')
                ->where('akun_transaksi_proyeks.id_perusahaan', Auth::user()->id_perusahaan)
                ->where('akun_transaksi_proyeks.jenis', 'Keluar')
                ->join("manajemen","manajemen.id","akun_transaksi_proyeks.idManajemen")
                ->groupBy("manajemen.id")
                ->get();
        $biayas1 = AkunTransaksiProyek::where('akun_transaksi_proyeks.id_perusahaan', Auth::user()->id_perusahaan)
                ->where('akun_transaksi_proyeks.jenis', 'Keluar')
                ->join("manajemen","manajemen.id","akun_transaksi_proyeks.idManajemen")
                ->join("anggaran_proyek","anggaran_proyek.id_akun_tr_proyek","akun_transaksi_proyeks.id")
                ->groupBy("manajemen.id")
                ->selectRaw('manajemen.*, sum(anggaran_proyek.nominal) as sum')
                ->get();

                
        foreach($biayas as $biaya){
            $biaya->jumlah = AkunTransaksiProyek::join("anggaran_proyek","anggaran_proyek.id_akun_tr_proyek","akun_transaksi_proyeks.id")
            ->where([["akun_transaksi_proyeks.idManajemen",$biaya->id],['akun_transaksi_proyeks.id_perusahaan', Auth::user()->id_perusahaan]])->sum("anggaran_proyek.nominal");
            $biaya->realisasi = TransaksiProyek::where("id_akun_tr_proyek",$biaya->id)->sum("jumlah");
        }
        // dd($biayas);
        // dd($biayas[0]);

        // $biayas = AkunTransaksiProyek::join("anggaran_proyek","anggaran_proyek.id_akun_tr_proyek","akun_transaksi_proyeks.id")
        // ->select('akun_transaksi_proyeks.*','manajemen.*')
        // ->where('akun_transaksi_proyeks.id_perusahaan', Auth::user()->id_perusahaan)
        // ->where('akun_transaksi_proyeks.jenis', 'Keluar')
        // ->join("manajemen","manajemen.id","akun_transaksi_proyeks.idManajemen")
        // ->groupBy("manajemen.id")
        // ->get();
        // $jenisBiayaChild = Manajemen::whereNotNull('idParent')->get();

        $perusahaan = Perusahaan::with('user')->get()->where('kode_perusahaan', '=', Auth::user()->kode_perusahaan)->first();
        // dd($anggarans, $realisasis);
        return view('laporan/laba_rugi_proyek', compact('proyeks', 'curr_proyek',
        'pendapatans', 'biayas', 'start_date', 'end_date', 'date_range', 'perusahaan'));
    }
}
