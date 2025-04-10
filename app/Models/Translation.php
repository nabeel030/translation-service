<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Translation extends Model
{
    use HasFactory;
    
    protected $fillable = ['key', 'tag'];

    public function contents(): HasMany
    {
        return $this->hasMany(TranslationContent::class);
    }
}
