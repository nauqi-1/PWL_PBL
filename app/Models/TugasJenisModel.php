<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasJenisModel extends Model
{
    use HasFactory;

    protected $table = 't_tugas_jenis';
    protected $primaryKey = 'jenis_id';
    protected $fillable = ['jenis_nama'];

    public function tugas()
    {
        return $this->hasMany(TugasModel::class, 'jenis_id', 'jenis_id');
    }
}
