<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TendikModel extends Model
{
    use HasFactory;

    protected $table = 'm_tendik';
    protected $primaryKey = 'tendik_id';
    protected $fillable = ['tendik_nama','tendik_noHp','tendik_nip', 'user_id','created_at', 'updated_at'];

    public function user():BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user+_id');
    }
}
