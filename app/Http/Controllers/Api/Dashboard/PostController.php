<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseFormatter;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'category', 'comments')->when(request()->q, function ($posts) {
            $posts = $posts->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        //return with Api Resource
        return ResponseFormatter::success($posts, 'Data Post berhasil di dapatkan');
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
            'title'         => 'required|unique:posts',
            'category_id'   => 'required',
            'content'       => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $post = Post::create([
            'image'       => $image->hashName(),
            'title'       => $request->title,
            'slug'        => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'user_id'     => auth()->guard('api')->user()->id,
            'content'     => $request->content,
            'description' => $request->description
        ]);


        $post->save();

        if ($post) {
            //return success with Api Resource
            return ResponseFormatter::success($post, 'Data Post Berhasil Disimpan!');
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
        $post = Post::with('category')->whereId($id)->first();

        if ($post) {
            //return success with Api Resource
            return ResponseFormatter::success($post, 'Detail Data Post');
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
    public function update(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|unique:posts,title,' . $post->id,
            'category_id'   => 'required',
            'content'       => 'required',
            'description'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/posts/' . basename($post->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            $post->update([
                'image'       => $image->hashName(),
                'title'       => $request->title,
                'slug'        => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'user_id'     => auth()->guard('api')->user()->id,
                'content'     => $request->content,
                'description' => $request->description
            ]);
        }

        $post->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'user_id'     => auth()->guard('api')->user()->id,
            'content'     => $request->content,
            'description' => $request->description
        ]);


        $post->save();

        if ($post) {
            //return success with Api Resource
            return ResponseFormatter::success($post, 'Data Post Berhasil Diupdate!');
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
    public function destroy(Post $post)
    {

        //remove image
        Storage::disk('local')->delete('public/posts/' . basename($post->image));

        if ($post->delete()) {
            //return success with Api Resource
            return ResponseFormatter::success(null, 'Data Post Berhasil Dihapus!');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data Post Gagal Dihapus!', 500);
    }
}
