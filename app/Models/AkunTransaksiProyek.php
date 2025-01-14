<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AkunTransaksiProyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'jenis','id_perusahaan', 'idmanajemen',
    ];

    public function catatan_transaksi_proyek(){
        return $this->hasMany('\App\Models\Catatan\TransaksiProyek', 'id', 'id_akun_tr_proyek');
    }

    public function anggaran(){
        return $this->hasMany('\App\Models\Catatan\Anggaran', 'id', 'id_akun_tr_proyek');
    }

    public function manajemen(){
        return $this->hasMany('\App\Models\Manajemen', 'idmanajemen',  'id');
    }
}
