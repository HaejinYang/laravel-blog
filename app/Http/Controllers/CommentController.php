<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Requests\Comment\CommentDeleteFormRequest;
use App\Requests\Comment\CommentSearchFormRequest;
use App\Requests\Comment\CommentStoreFormRequest;
use App\Requests\Comment\CommentUpdateFormRequest;
use App\Services\CommentService;

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
    public function store(CommentStoreFormRequest $formRequest)
    {
        $request = $formRequest->toRequest();
        $response = $this->commentService->save($request);

        return $response;
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
    public function update(CommentUpdateFormRequest $formRequest, Comment $comment)
    {
        $request = $formRequest->toRequest();
        $response = $this->commentService->update($comment, $request);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CommentDeleteFormRequest $formRequest, Comment $comment)
    {
        $request = $formRequest->toRequest();
        $response = $this->commentService->delete($comment, $request);

        return $response;
    }
}
