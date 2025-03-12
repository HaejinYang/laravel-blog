<?php

namespace App\Http\Controllers;

use App\Requests\PostSearchFormRequest;
use App\Requests\PostStoreFormRequest;
use App\Requests\PostUpdateFormRequest;
use App\Responses\PostResponse;
use App\Services\PostService;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function __construct(
        private PostService $postService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PostSearchFormRequest $request): array
    {
        $validated = $request->toRequest();

        $response = $this->postService->getMany($validated);

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
    public function store(PostStoreFormRequest $request)
    {
        $validated = $request->toRequest();
        $post = $this->postService->save($validated);

        return response()->json($post, Response::HTTP_OK);
    }

    public function show(string $postId): PostResponse
    {
        $post = $this->postService->getOne($postId);

        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateFormRequest $formRequest, string $id)
    {
        $request = $formRequest->toRequest();
        $post = $this->postService->update($id, $request);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
