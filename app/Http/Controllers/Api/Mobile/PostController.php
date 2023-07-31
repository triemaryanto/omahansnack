<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Post;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'category', 'comments')->when(request()->q, function ($posts) {
            $posts = $posts->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(3);

        //return sukses menggunakan ResponseFormatter
        return ResponseFormatter::success($posts, 'Data posts berhasil di dapatkan');
    }
    public function singlePost()
    {
        $posts = Post::with('user', 'category', 'comments')->when(request()->q, function ($posts) {
            $posts = $posts->where('title', 'like', '%' . request()->q . '%');
        })->inRandomOrder()->paginate(1);

        //return sukses menggunakan ResponseFormatter
        return ResponseFormatter::success($posts, 'Data posts berhasil di dapatkan');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $post = Post::with('user', 'category', 'comments')->whereId($id)->first();

        if ($post) {
            //return sukses menggunakan ResponseFormatter
            return ResponseFormatter::success($post, 'Data Post Berdasarkan ID berhasil di dapatkan');
        }

        //return error menggunakan ResponseFormatter
        return ResponseFormatter::error(null, 'Data Post berdasarkan ID gagal di dapatkan', 404);
    }
    /**
     * storeComment
     *
     * @param  mixed $request
     * @return void
     */
    public function storeComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email',
            'comment'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //get Post by slug
        $post = Post::where('slug', $request->slug)->first();

        //store comment
        $post->comments()->create([
            'name'      => $request->name,
            'email'     => $request->email,
            'comment'   => $request->comment
        ]);

        return ResponseFormatter::success('Comment Berhasil Disimpan!', $post->comments()->get());
    }
}
