<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_댓글_리스트_조회(): void
    {
        // given
        $post = Post::create([
            'title' => '테스트 포스트',
            'content' => '테스트 포스트 내용',
            'author' => '테스트 작성자',
        ]);

        // when
        $postId = $post->id;
        $response = $this->getJson("/api/posts/{$postId}/comments");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertContent('comments in post');
    }

    public function test_특정_댓글_조회(): void
    {
        // given
        $comment = Comment::create([
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'post_id' => 1,
        ]);

        // when
        $commentId = $comment->id;
        $response = $this->getJson("/api/comments/{$commentId}");
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $commentId,
            'content' => '테스트 댓글 내용',
            'author' => '테스트 작성자'
        ]);
    }

    public function test_없는_댓글_조회(): void
    {
        // when
        $commentId = 9999;
        $response = $this->getJson("/api/comments/{$commentId}");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'message' => '존재하지 않는 댓글입니다.',
            "code" => 404
        ]);
    }
}
