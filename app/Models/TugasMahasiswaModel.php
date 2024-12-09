<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasMahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 't_tugas_mahasiswa';
    protected $primaryKey = 'tugas_mahasiswa_id';
    protected $fillable = [
        'tugas_id',
        'mahasiswa_id',
        'status',
        'file_path',  // Tambahkan file_path ke fillable
        'progress',    // Tambahkan progres ke fillable
        'tanggal_disubmit',
    ];

    /**
     * Relasi dengan TugasModel
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tugas()
    {
        return $this->belongsTo(TugasModel::class, 'tugas_id', 'tugas_id');
    }
    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
