<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Requests\Comment\CommentSearchFormRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CommentSearchFormRequest $formRequest)
    {
        $request = $formRequest->toRequest();
        $response = $this->commentService->getMany($request);

        return $response;
    }

    public function indexByPost(Post $post, CommentSearchFormRequest $formRequest)
    {
        $request = $formRequest->toRequest();
        $response = $this->commentService->getMany($request, $post->id);

        return $response;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $commentId)
    {
        $comment = $this->commentService->getOne($commentId);

        return $comment;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
