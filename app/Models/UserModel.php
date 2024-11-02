<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserModel extends Model
{
    use HasFactory;

    protected $table    = 'm_user';
    protected $primaryKey  = 'user_id';
    protected $fillable = ['level_id','username','password', 'created_at', 'updated_at'];
    protected $hidden   = ['password'];

    protected $casts    = ['password' => 'hashed'];


    public function level():BelongsTo
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function tugas():HasMany
    {
        return $this->hasMany(TugasModel::class, 'tugas_pembuat_id', 'user_id');
    }

    //mendapatkan nama role

    public function getRoleName(): string {
        return $this->level->level_nama;
    }

    //cek apakah user memiliki kode tertentu

    public function hasRole($role): bool {
        return $this->level->level_kode == $role;
    }

    //mendapatkan kode role
    public function getRole(): string {
        return $this->level->level_kode;
    }
}
