<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaAlfaModel extends Model
{
    use HasFactory;

    protected $table = 't_mahasiswa_alfa';
    protected $primaryKey = 'mahasiswa_alfa_id';

    protected $fillable = ['mahasiswa_id','periode_id','jumlah_alfa'];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function periode()
    {
        return $this->belongsTo(PeriodeModel::class, 'periode_id', 'periode_id');
    }
}
