<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    // Jika tabelmu bernama m_level:
    protected $table = 'm_level';

    // **PENTING**: sesuaikan primary key
    protected $primaryKey = 'level_id';
    
    // Jika PK-nya auto-increment integer
    public $incrementing = true;
    protected $keyType = 'int';

    // Jika timestamp (created_at, updated_at) ada
    public $timestamps = true;

    // Kolom yang boleh di-fill massal
    protected $fillable = ['level_kode', 'level_nama'];
}
