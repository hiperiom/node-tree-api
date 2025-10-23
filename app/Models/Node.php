<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ['parent_id', 'title'];
    protected $casts = [
        'created_at' => 'datetime',
    ];
    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Node::class, 'parent_id');
    }
}
