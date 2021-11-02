<?php
namespace App\Traits;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait Filterable
{
    public function scopeFilter($query, $param)
    {
        foreach ($param as $field => $value) {
            $method = 'filter' . Str::studly($field); //Convert to stydly case

            if ($field == 'search')
            {
                foreach ($value as $i => $subVal) {
                    if (method_exists($this, $method)) {
                        $this->{$method}($query, $subVal);
                    }
                }
                continue;
            }

            if ($value === '') {
                continue;
            }
    
            if (method_exists($this, $method)) {
                $this->{$method}($query, $value);
                continue;
            }
    
            if (empty($this->filterable) || !is_array($this->filterable)) {
                continue;
            }
    
            if (in_array($field, $this->filterable)) {
                $query->where($this->table . '.' . $field, 'LIKE' , '%'.$value.'%');
                continue;
            }
    
            if (key_exists($field, $this->filterable)) {
                $query->where($this->table . '.' . $this->filterable[$field], 'LIKE', '%'.$value.'%');
                continue;
            }
        }

        return $query;
    }
}
