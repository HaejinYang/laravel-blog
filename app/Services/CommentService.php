<?php

namespace App\Services;

use App\Exceptions\CommentNotFound;
use App\Exceptions\CommentPasswordNotMatch;
use App\Exceptions\PostNotFound;
use App\Models\Comment;
use App\Models\Post;
use App\Requests\Comment\CommentDeleteRequest;
use App\Requests\Comment\CommentSearchRequest;
use App\Requests\Comment\CommentStoreRequest;
use App\Requests\Comment\CommentUpdateRequest;
use App\Responses\CommentResponse;

class CommentService
{
    /**
     * @param int $commentId 댓글ID
     * @return CommentResponse
     */
    public function getOne(int $commentId): CommentResponse
    {
        $comment = Comment::findOr($commentId, fn() => throw new CommentNotFound());
        $response = new CommentResponse($comment);

        return $response;
    }

    /**
     * @param int $postId 게시글ID
     * @return CommentResponse[]
     */
    public function getMany(CommentSearchRequest $request, int $postId = -1): array
    {
        $page = $request->getPage() - 1;
        $pageSize = $request->getPageSize();
        $orderBy = $request->getOrderBy();

        // 1) 쿼리 빌더 시작
        $query = Comment::query();

        // 2) postId가 -1이 아니라면 조건 추가
        if ($postId !== -1) {
            $query->where('post_id', $postId);
        }

        // 3) 나머지 정렬, 페이징 처리
        $response = $query->orderBy('id', $orderBy)
            ->paginate($pageSize, ['*'], 'page', $page)
            ->map(fn($comment) => new CommentResponse($comment))
            ->toArray();

        return $response;
    }

    public function save(CommentStoreRequest $request)
    {
        $postId = $request->getPostId();
        Post::findOr($postId, fn() => throw new PostNotFound());

        $comment = Comment::create($request->toArray());
        $response = new CommentResponse($comment);

        return $response;
    }

    public function update(Comment $comment, CommentUpdateRequest $formRequest): CommentResponse
    {
        $comment->update($formRequest->toArray());
        $comment->save();
        $response = new CommentResponse($comment);
        return $response;
    }

    public function delete(Comment $comment, CommentDeleteRequest $request): CommentResponse
    {
        $save = $comment;
        if ($request->getPassword() !== $comment->password) {
            throw new CommentPasswordNotMatch();
        }

        $response = new CommentResponse($save);
        return $response;
    }
}
