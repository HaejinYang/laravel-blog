<?php

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
}
