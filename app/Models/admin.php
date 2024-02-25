<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin extends Model
{
    use HasFactory;
    protected $fillable =[
        'username', 'password', 'login_tokens', 'users_id', 'absensis_id'
    ];

    
}
