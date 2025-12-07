<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryRepository
{
    public function getAll()
    {
        return Category::all();
    }
    
    public function getAllCached()
    {
        return Cache::remember('categories:all', 3600, function () {
            return $this->getAll();
        });
    }
}