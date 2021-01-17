<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Relationship extends Model
{
    use HasFactory;

    protected $fillable = ['user_first_id', 'user_second_id', 'status'];

    protected $table = 'relationships';
    
    public $timestamps = true;


    public function getUserOne() {
        return $this->belongsTo(User::class ,'user_first_id' , 'id');
    }

    public function getUserTwo() {
        return $this->belongsTo(User::class, 'user_second_id', 'id');
    }

}
