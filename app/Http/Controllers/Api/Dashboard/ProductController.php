<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->when(request()->q, function ($products) {
            $products = $products->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        //return with Api Resource
        return ResponseFormatter::success($products, 'Data Post berhasil di dapatkan');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name'         => 'required|unique:products',
            'category_id'   => 'required',
            'price'       => 'required',
            'types'       => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        $product = Product::create([
            'image'       => $image->hashName(),
            'name'       => $request->name,
            'category_id' => $request->category_id,
            'price'     => $request->price,
            'types'     => $request->types,
            'description' => $request->description
        ]);
        if ($product) {
            //return success with Api Resource
            return ResponseFormatter::success($product, 'Data Post Berhasil Disimpan!');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data Post Gagal Di Simpan', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('category')->whereId($id)->first();

        if ($product) {
            //return success with Api Resource
            return ResponseFormatter::success($product, 'Detail Data Post');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Detail Data Post Tidak DItemukan!', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|unique:products,name,' . $product->id,
            'category_id'   => 'required',
            'price'       => 'required',
            'types'       => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/products/' . basename($product->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            $product->update([
                'image'       => $image->hashName(),
                'name'       => $request->name,
                'category_id' => $request->category_id,
                'price'     => $request->price,
                'types'     => $request->types,
                'description' => $request->description
            ]);
        }

        $product->update([
            'name'       => $request->name,
            'category_id' => $request->category_id,
            'price'     => $request->price,
            'types'     => $request->types,
            'description' => $request->description
        ]);


        $product->save();

        if ($product) {
            //return success with Api Resource
            return ResponseFormatter::success($product, 'Data Post Berhasil Diupdate!');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data Post Gagal Diupdate!', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        //remove image
        Storage::disk('local')->delete('public/products/' . basename($product->image));

        if ($product->delete()) {
            //return success with Api Resource
            return ResponseFormatter::success(null, 'Data Post Berhasil Dihapus!');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data Post Gagal Dihapus!', 500);
    }
}
