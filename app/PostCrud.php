<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PostCrud extends Model
{
    use SoftDeletes;

    protected $hidden = ["deleted_at"];
    
    protected $fillable = [
        'user_id', 'title', 'slug', 'description', 'image' 
       ];
}
