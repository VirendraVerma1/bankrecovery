<?php

namespace app\Http\Controllers\Api\Traits;

Trait SearchTrait{
    public function scopeSearchStrict($query, $field, $search){

        if($search != ''){
            return $query->where($field,$search);
        }
    }
    public function scopeSearch($query, $field, $search){

        if($search != ''){
            return $query->where($field, 'like', '%'.$search.'%');
        }
    }


}