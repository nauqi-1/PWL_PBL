<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;

    protected $table = 'm_periode';
    protected $primaryKey = 'periode_id';
    protected $fillable = ['periode', 'created_at', 'updated_at'];

    public function mahasiswa_alfa() {
        return $this->hasMany(MahasiswaAlfaModel::class, 'periode_id','periode_id');
    } 
}
