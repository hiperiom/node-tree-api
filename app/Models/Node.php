<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ['parent_id', 'title'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at'=> 'datetime',
    ];
    public function parent()
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Node::class, 'parent_id');
    }
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                  
                $timezone = app('current.timezone');
                
                return Carbon::parse($value)->setTimezone($timezone)->toDateTimeString();
            },
        );
    }
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                  
                $timezone = app('current.timezone');
                
                return Carbon::parse($value)->setTimezone($timezone)->toDateTimeString();
            },
        );
    }
}
