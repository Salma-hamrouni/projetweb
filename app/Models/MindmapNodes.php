<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MindmapNode extends Model
{
    protected $fillable = [
        'titre',
        'contenu',
        'parent_id',
    ];

    // Un nœud peut avoir plusieurs enfants
    public function enfants(): HasMany
    {
        return $this->hasMany(MindmapNode::class, 'parent_id');
    }

    // Un nœud peut avoir un parent
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MindmapNode::class, 'parent_id');
    }
}
