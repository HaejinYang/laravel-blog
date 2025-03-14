<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_포스트_없는_댓글_리스트_조회(): void
    {
        // given
        for ($i = 0; $i < 3; $i++) {
            Comment::create([
                'author' => '테스트 작성자',
                'password' => '1234',
                'content' => '테스트 댓글 내용',
                'postId' => 1,
            ]);
        }

        // when
        $response = $this->getJson("/api/comments");

        // then
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
        $data = $response->json();
        $this->assertEquals('테스트 댓글 내용', $data[0]['content']);
        $this->assertEquals(3, $data[0]['id']);
    }

    public function test_포스트에_포함된_댓글_리스트_조회(): void
    {
        // given
        $post = Post::create([
            'title' => '테스트 포스트',
            'content' => '테스트 포스트 내용',
            'author' => '테스트 작성자',
            'userId' => 1,
        ]);

        for ($i = 0; $i < 3; $i++) {
            Comment::create([
                'author' => '테스트 작성자',
                'password' => '1234',
                'content' => '테스트 댓글 내용',
                'postId' => $post->id,
            ]);
        }
        // when
        $postId = $post->id;
        $response = $this->getJson("/api/posts/{$postId}/comments");

        // then
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
        $data = $response->json();
        $this->assertEquals('테스트 댓글 내용', $data[0]['content']);
        $this->assertEquals(3, $data[0]['id']);
    }

    public function test_특정_댓글_조회(): void
    {
        // given
        $comment = Comment::create([
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'postId' => 1,
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

    public function test_포스트에_댓글_추가(): void
    {
        // given
        $post = Post::create([
            'title' => '테스트 포스트',
            'content' => '테스트 포스트 내용',
            'author' => '테스트 작성자',
            'userId' => 1,
        ]);

        $request = [
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'postId' => $post->id,
        ];

        // when
        $response = $this->postJson("/api/comments", $request);

        // then
        $response->assertStatus(Response::HTTP_OK);
        $data = $response->json();
        $this->assertEquals(1, Comment::count());
        $this->assertEquals('테스트 댓글 내용', Comment::first()->content);
        $this->assertEquals('테스트 작성자', Comment::first()->author);
    }

    public function test_존재하지_않는_포스트에_댓글_추가는_실패해야함(): void
    {
        // given
        $request = [
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'postId' => 3,
        ];

        // when
        $response = $this->postJson("/api/comments", $request);

        // then
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_존재하는_댓글_수정(): void
    {
        // given
        $comment = Comment::create([
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'postId' => 1,
        ]);
        $request = [
            'content' => '수정 댓글 내용',
            'password' => '1234'
        ];

        // when
        $response = $this->patchJson("/api/comments/{$comment->id}", $request);

        // then
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_존재하지_않는_댓글_수정(): void
    {
        // given
        $request = [
            'content' => '수정 댓글 내용',
            'password' => '1234'
        ];
        $commentId = 999999999;
        // when
        $response = $this->patchJson("/api/comments/{$commentId}", $request);

        // then
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_존재하는_댓글_비밀번호_일치하면_삭제성공(): void
    {
        // given
        $comment = Comment::create([
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'postId' => 1,
        ]);
        $request = [
            'password' => '1234',
        ];

        // when
        $response = $this->delete("/api/comments/{$comment->id}", $request);

        // then
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_존재하는_댓글_비밀번호_다르면_삭제실패(): void
    {
        // given
        $comment = Comment::create([
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'postId' => 1,
        ]);
        $request = [
            'password' => '12345',
        ];

        // when
        $response = $this->delete("/api/comments/{$comment->id}", $request);

        // then
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_존재하지_않는_댓글_삭제_실패해야함(): void
    {
        // given
        $request = [
            'content' => '수정 댓글 내용',
        ];
        $commentId = 999999999;
        // when
        $response = $this->patchJson("/api/comments/{$commentId}", $request);

        // then
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
