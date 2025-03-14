<?php

namespace App\Http\Controllers;

use App\Requests\Post\PostSearchFormRequest;
use App\Requests\Post\PostStoreFormRequest;
use App\Requests\Post\PostUpdateFormRequest;
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
     * 포스트 리스트 조회
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
     * 포스트 생성
     */
    public function store(PostStoreFormRequest $formRequest)
    {
        $request = $formRequest->toRequest();
        $post = $this->postService->save($request);

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
    public function update(PostUpdateFormRequest $formRequest, int $postId)
    {
        $request = $formRequest->toRequest();
        $post = $this->postService->update($postId, $request);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $postId)
    {
        $this->postService->delete($postId);

        return "delete";
    }
}
