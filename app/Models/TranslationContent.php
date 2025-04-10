<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TranslationContent extends Model
{
    use HasFactory;
    
    protected $fillable = ['locale', 'content', 'translation_id'];

    public function translation(): BelongsTo
    {
        return $this->belongsTo(Translation::class);
    }
}
