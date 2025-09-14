<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Prevent any database operations
    public $timestamps = false;
    
    // Prevent any database operations
    protected $guarded = [];
    
    // Prevent any database operations
    public static function boot()
    {
        parent::boot();
        
        static::creating(function($model) {
            return false;
        });
        
        static::updating(function($model) {
            return false;
        });
        
        static::saving(function($model) {
            return false;
        });
    }
    
    // Dummy relationship
    public function user()
    {
        return new class {
            public function getKey() { return null; }
            public function getAttribute($key) { return null; }
        };
    }
}
