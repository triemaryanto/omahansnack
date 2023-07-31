<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ResponseFormatter;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $types = $request->input('types');


        if ($id) {
            $product = Product::find($id);

            if ($product)
                return ResponseFormatter::success(
                    $product,
                    'Data produk berhasil diambil'
                );
            else
                return ResponseFormatter::error(
                    null,
                    'Data produk tidak ada',
                    404
                );
        }

        $product = Product::query();

        if ($name)
            $product->where('name', 'like', '%' . $name . '%');

        if ($types)
            $product->where('types', 'like', '%' . $types . '%');

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data list produk berhasil diambil'
        );
    }
    public function show($id)
    {

        $post = Product::with('category')->whereId($id)->first();

        if ($post) {
            //return sukses menggunakan ResponseFormatter
            return ResponseFormatter::success($post, 'Data Post Berdasarkan ID berhasil di dapatkan');
        }

        //return error menggunakan ResponseFormatter
        return ResponseFormatter::error(null, 'Data Post berdasarkan ID gagal di dapatkan', 404);
    }
}
