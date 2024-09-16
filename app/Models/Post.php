<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

     // The table associated with the model.
     protected $table = 'posts';

     // The attributes that are mass assignable.
     protected $fillable = ['title', 'content'];
     // If you want to disable timestamps
     public $timestamps = false;
}
