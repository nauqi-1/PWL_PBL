<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminModel extends Model
{
    use HasFactory;
    protected $table = 'm_admin';
    protected $primaryKey = 'admin_id';
    protected $fillable = ['admin_nama', 'admin_prodi', 'user_id', 'created_at', 'updated_at', 'admin_noHp'];

    public function user():BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
    public function tugas()
    {
        return $this->hasMany(TugasModel::class,'tugas_pembuat_id', 'user_id');
    }
}
