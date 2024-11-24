<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DosenModel extends Model
{
    use HasFactory;
    protected $table = 'm_dosen';
    protected $primaryKey = 'dosen_id';
    protected $fillable = ['dosen_nama', 'dosen_prodi', 'dosen_noHp', 'dosen_nip','user_id',  'created_at', 'updated_at'];

    public function user():BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id','user_id');
    }

    public function tugas()
    {
        return $this->hasMany(TugasModel::class,'tugas_pembuat_id', 'user_id');
    }


}
