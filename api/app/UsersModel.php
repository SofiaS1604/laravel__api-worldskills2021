<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UsersModel extends Model
{

    protected $table = 'users';
    protected $fillable = [
        'first_name', 'last_name', 'phone',
        'password', 'document_number', 'api_token',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    public function saveToken($user){
        $user->api_token = Str::random();
        $user->save();

        return $user->api_token;
    }
}
