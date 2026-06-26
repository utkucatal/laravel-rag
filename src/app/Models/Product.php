<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'url';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];

    public function searchableText(): string
    {
        return collect([
            $this->title, $this->manufacturer, $this->oem_pn,
            $this->category, $this->condition, $this->description
        ])->filter()->implode(' | ');
    }
}
