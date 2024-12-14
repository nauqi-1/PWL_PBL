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
    protected $table = 'm_mahasiswa';
    protected $primaryKey = 'mahasiswa_id';
    protected $fillable = [
        'mahasiswa_nama',
        'mahasiswa_kelas',
        'mahasiswa_nim',
        'mahasiswa_prodi',
        'mahasiswa_noHp',
        'mahasiswa_alfa_lunas',
        'user_id',
        'created_at',
        'updated_at'

    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function tugas(): BelongsToMany
    {
        return $this->belongsToMany(TugasModel::class, 't_tugas_mahasiswa', 'mahasiswa_id', 'tugas_id');
    }

    public function pengumpulan()
    {
        return $this->hasMany(TugasMahasiswaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function mahasiswa_alfa()
    {
        return $this->hasMany(MahasiswaAlfaModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
