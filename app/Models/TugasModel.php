<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TugasModel extends Model
{
    use HasFactory;
    protected $table = 't_tugas';
    protected $primaryKey = 'tugas_id';
    protected $fillable = [
        'tugas_nama', 
        'tugas_desc', 
        'tugas_bobot', 
        'tugas_file', 
        'tugas_status', //O = open (belum dikerjakan), W=working(sedang dikerjakan), S = submitted (sudah dikumpulkan), D = done(sudah diterima)
        'tugas_tgl_dibuat',
        'tugas_tgl_deadline',
        'tugas_pembuat_id',
        'tugas_progress'
    ];

    public function tugas_pembuat_id():BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'tugas_pembuat_id', 'user_id');
    }

    public function kompetensi()
    {
        return $this->belongsToMany(KompetensiModel::class,'t_detail_kompetensi', 'tugas_id','tugas_id');
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(MahasiswaModel::class,'t_detail_mahasiswa', 'tugas_id', 'tugas_id');
    }
        
}
