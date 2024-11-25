<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TugasMahasiswaModel extends Model
{
    use HasFactory;
    protected $table = 't_tugas_mahasiswa';
    protected $primaryKey = 'tugas_mahasiswa_id';
    protected $fillable = ['tugas_id', 'mahasiswa_id', 'status'];
}
