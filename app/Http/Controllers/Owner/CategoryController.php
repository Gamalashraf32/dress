<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ResponseTrait;
    public function showcat()
    {
        $cat = Category::get();
        return $this->returnData('categories', $cat, 200);
    }
}
