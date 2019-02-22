<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrawlingList extends Model
{
    public function scopeIsthere($query,$id)
    {
        return $query->where('uniq_id', $id);
    }
}
