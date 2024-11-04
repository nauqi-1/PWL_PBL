<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TugasModel extends Model
{
    use HasFactory;
    protected $table = 't_tugas';
    protected $primaryKey = 'tugas_id';
    protected $fillable = [
        'tugas_nama', 
        'tugas_desc', 
        'tugas_bobot', 
        'tugas_file', 
        'tugas_status', 
        'tugas_tgl_dibuat',
        'tugas_tgl_deadline',
        'tugas_pembuat_id'
    ];

    public function tugas_pembuat_id():BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'tugas_pembuat_id');
    }
        
}
