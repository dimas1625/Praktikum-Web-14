<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shoes extends Model
{
    use HasFactory; //Mengimpor trait HasFactory ke dalam model.
    protected $fillable = ['Merk', 'color', 'size']; //Menentukan atribut-atribut mana yang dapat diisi secara massal (mass assignable) pada model tersebut.
}