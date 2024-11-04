<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PengumpulanModel extends Model
{
    use HasFactory;
    protected $table = 't_pengumpulan';
    protected $primaryKey = 'pengumpulan_id';
    protected $fillable = [
        'pengumpulan_tugas_id',
        'pengumpulan_pembuat_id',
        'pengumpulan_tanggal',
        'pengumpulan_file',
    ];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(TugasModel::class, 'pengumpulan_tugas_id', 'tugas_id');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'pengumpulan_pembuat_id', 'user_id');
    }

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(MahasiswaModel::class, 't_pengumpulan_mahasiswa', 'pengumpulan_id', 'mahasiswa_id');
    }
}
