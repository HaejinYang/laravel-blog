<?php

namespace App\Services;

use App\Exceptions\PostNotFound;
use App\Models\Post;
use App\Requests\PostStoreRequest;
use App\Responses\PostResponse;

class PostService
{
    public function getOne(int $postId): PostResponse
    {
        $post = Post::findOr($postId, fn() => throw new PostNotFound());
        $response = new PostResponse($post);

        return $response;
    }

    /**
     * @return PostResponse[]
     */
    public function getMany(): array
    {
        $response = Post::all()->map(fn($post) => new PostResponse($post))->toArray();

        return $response;
    }

    public function save(PostStoreRequest $request): PostResponse
    {
        $post = Post::create($request->toArray());
        $response = new PostResponse($post);

        return $response;
    }
}
