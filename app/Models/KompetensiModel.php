<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiModel extends Model
{
    use HasFactory;
    protected $table = 'm_kompetensi';
    protected $primaryKey = 'kompetensi_id';
    protected $fillable = ['kompetensi_nama', 'created_at', 'updated_at'];
}
