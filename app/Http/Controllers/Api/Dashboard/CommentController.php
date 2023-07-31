<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::when(request()->q, function ($comments) {
            $comments = $comments->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        //return with Api Resource
        return ResponseFormatter::success($comments, 'Data Post berhasil di dapatkan');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {

        if ($comment->delete()) {
            //return success with Api Resource
            return ResponseFormatter::success(null, 'Data Category Berhasil Dihapus!');
        }

        //return failed with Api Resource
        return ResponseFormatter::error(null, 'Data Category Gagal Dihapus!', 500);
    }
}
