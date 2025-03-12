<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    // Jika nama tabel di database adalah "m_user"
    protected $table = 'm_user';
    
    // Jika primary key tabel adalah "user_id"
    protected $primaryKey = 'user_id';

    // Tentukan field yang boleh di-mass assign
    protected $fillable = ['username', 'nama', 'level_id', 'password'];
}
