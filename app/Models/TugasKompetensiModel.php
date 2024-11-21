<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TugasKompetensiModel extends Model
{
    use HasFactory;

    protected $table = 't_tugas_kompetensi';
    protected $primaryKey = 'tugas_kompetensi_id';
    protected $fillable = ['tugas_id', 'kompetensi_id', 'created_at', 'updated_at'];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(TugasModel::class, 'tugas_id', 'tugas_id');
    }

    public function kompetensi(): BelongsTo
    {
        return $this->belongsTo(KompetensiModel::class, 'kompetensi_id', 'kompetensi_id');
    }
}
