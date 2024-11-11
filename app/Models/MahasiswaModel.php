<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MahasiswaModel extends Model
{
    use HasFactory;
    protected $table= 'm_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';
    protected $fillable = [
        'mahasiswa_nama', 
        'mahasiswa_kelas', 
        'mahasiswa_nim', 
        'mahasiswa_prodi', 
        'mahasiswa_noHp',
        'mahasiswa_alfa_sisa',
        'mahasiswa_alfa_total',
        'user_id',
        'created_at', 'updated_at'

    ];

    public function user():BelongsTo 
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function tugas():BelongsToMany
    {
        return $this->belongsToMany(TugasModel::class, 't_tugas_mahasiswa', 'mahasiswa_id','tugas_id');
    }

    public function pengumpulan(): BelongsToMany
    {
        return $this->belongsToMany(PengumpulanModel::class, 't_pengumpulan_mahasiswa', 'mahasiswa_id', 'pengumpulan_id');
    }
}