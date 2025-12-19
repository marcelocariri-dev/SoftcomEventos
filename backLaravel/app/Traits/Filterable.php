<?php

namespace App\Traits;

use App\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Builder;


Trait Filterable {
    public function scopeFilter(Builder $query, QueryFilter $filters)
{
        return $filters->apply($query);
}}