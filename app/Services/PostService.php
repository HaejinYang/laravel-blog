<?php

namespace App\Services;

use App\Exceptions\PostNotFound;
use App\Models\Post;
use App\Requests\PostSearchRequest;
use App\Requests\PostStoreRequest;
use App\Responses\PostResponse;

class PostService
{
    /**
     * @return PostResponse
     */
    public function getOne(int $postId): PostResponse
    {
        $post = Post::findOr($postId, fn() => throw new PostNotFound());
        $response = new PostResponse($post);

        return $response;
    }

    /**
     * @return PostResponse[]
     */
    public function getMany(PostSearchRequest $postSearchRequest): array
    {
        $page = $postSearchRequest->getPage() - 1;
        $pageSize = $postSearchRequest->getPageSize();
        $orderBy = $postSearchRequest->getOrderBy();

        $response = Post::orderBy('id', $orderBy)->paginate($pageSize, ['*'], 'page', $page)->map(fn($post) => new PostResponse($post))->toArray();

        return $response;
    }

    /**
     * @return PostResponse
     */
    public function save(PostStoreRequest $request): PostResponse
    {
        $post = Post::create($request->toArray());
        $response = new PostResponse($post);

        return $response;
    }
}
