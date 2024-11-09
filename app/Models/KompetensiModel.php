<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'm_kompetensi';
    protected $primaryKey = 'kompetensi_id';
    protected $fillable = ['kompetensi_nama', 'created_at', 'updated_at'];

    public function tugas():BelongsToMany
    {
        return $this->belongsToMany(TugasModel::class, 't_tugas_kompetensi', 'kompetensi_id','tugas_id');
    }
}
