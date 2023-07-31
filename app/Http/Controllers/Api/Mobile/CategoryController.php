<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Helpers\ResponseFormatter;

class CategoryController extends Controller
{
    public function index()
    {
        //get categories
        $categories = Category::when(request()->q, function ($categories) {
            $categories = $categories->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(3);

        //return with Api Resource
        return ResponseFormatter::success($categories, 'Data Category berhasil di dapatkan');
    }
    public function indexSecond()
    {
        //get categories
        $categories = Category::when(request()->q, function ($categories) {
            $categories = $categories->where('name', 'like', '%' . request()->q . '%');
        })->oldest()->paginate(3);

        //return with Api Resource
        return ResponseFormatter::success($categories, 'Data Category berhasil di dapatkan');
    }
    public function all()
    {
        $categories = Category::all();

        //return sukses menggunakan ResponseFormatter
        return ResponseFormatter::success($categories, 'Data posts berhasil di dapatkan');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $category = Category::with('posts.category', 'posts.comments')->where('slug', $slug)->first();

        if ($category) {
            //return with Api Resource
            return ResponseFormatter::success($category, 'Data Category Berdasarkan id berhasil di dapatkan');
        }

        //return with Api Resource
        return ResponseFormatter::error(null, 'Data Category berdasarkan id gagal di dapatkan', 404);
    }
    public function showProduct($slug)
    {
        $category = Category::with('products.category',)->where('slug', $slug)->first();
        if ($category) {
            //return with Api Resource
            return ResponseFormatter::success($category, 'Data Category Berdasarkan id berhasil di dapatkan');
        }

        //return with Api Resource
        return ResponseFormatter::error(null, 'Data Category berdasarkan id gagal di dapatkan', 404);
    }
}
