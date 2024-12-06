<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $table = 't_request';
    protected $primaryKey = 'id_request';

    protected $fillable = [
        'tugas_id',
        'mhs_id',
        'tugas_pembuat_id',
        'status_request',
        'tgl_request',
        'tgl_update_status',
    ];

    /**
     * Relasi dengan model Tugas.
     */
    public function tugas()
    {
        return $this->belongsTo(TugasModel::class, 'tugas_id', 'tugas_id');
    }

    /**
     * Relasi dengan model Mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mhs_id', 'mahasiswa_id');
    }

    /**
     * Relasi dengan pembuat tugas (dosen).
     */
    public function pembuat()
    {
        return $this->belongsTo(UserModel::class, 'tugas_pembuat_id', 'user_id');
    }
}
