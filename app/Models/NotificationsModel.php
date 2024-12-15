<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationsModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't_notifications';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'notification_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jenis_notification',
        'pembuat_notification',
        'penerima_notification',
        'konten_notification',
        'tgl_notification',
        'status_notification',
        'tgl_notifdibaca',
        'ref_table',
        'ref_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tgl_notification' => 'datetime',
        'tgl_notifdibaca' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(UserModel::class, 'pembuat_notification', 'user_id');
    }
    public function recipient()
    {
        return $this->belongsTo(UserModel::class, 'penerima_notification', 'user_id');
    }
}
