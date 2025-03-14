<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\TestHelper\AuthHelper;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, AuthHelper;

    public function test_데이터베이스_드라이버_SQLite_memory_확인(): void
    {
        // expected
        $this->assertEquals('sqlite', config('database.default'));
        $this->assertEquals(':memory:', config('database.connections.sqlite.database'));
    }

    public function test_포스트_1개_조회(): void
    {
        // given
        Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
        ]);

        // when
        $response = $this->getJson('/api/posts');

        // then
        $data = $response->json();
        $response->assertStatus(200);
        $this->assertCount(1, $data);
    }

    public function test_포스트_여러개_조회(): void
    {
        // given
        $postCount = 5;
        collect(range(1, $postCount))->each(function ($i) {
            Post::create([
                'title' => '포스트 제목 {$i}',
                'content' => '포스트 내용 {$i}',
                'author' => '포스트 작성자 {$i}',
            ]);
        });

        // when
        $response = $this->getJson('/api/posts');

        // then
        $data = $response->json();
        $response->assertStatus(200);
        $this->assertCount($postCount, $data);
    }

    public function test_포스트_1개_특정해서_조회(): void
    {
        // given
        $post = Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
        ]);
        $id = $post->id;

        // when
        $response = $this->getJson("/api/posts/{$id}");

        // then
        $response->assertStatus(200);
        $response->assertJson([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
        ]);
    }

    public function test_포스트_없으면_에러응답_조회(): void
    {
        // given
        $id = 919191; // 없는 post id

        // when
        $response = $this->getJson("/api/posts/{$id}");

        // then
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJson([
            'code' => Response::HTTP_NOT_FOUND,
            'message' => "존재하지 않는 글입니다."
        ]);
    }

    public function test_포스트_생성(): void
    {
        // given
        $user = $this->actingAsAuthenticatedUser();
        $data = [
            'title' => 'Test Post',
            'content' => 'This is a test content.',
            'author' => 'John Doe',
            'userId' => $user->id,
        ];

        // when
        $response = $this->postJson('/api/posts', $data);

        // then
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(1, Post::count());
    }

    public function test_포스트_올바르지못한_생성은_실패해야함(): void
    {
        // given
        $user = $this->actingAsAuthenticatedUser();
        $data = [
            'title' => Str::random(300),
            'content' => 'This is a test content.',
            'author' => Str::random(300),
            'userId' => $user->id,
        ];

        // when
        $response = $this->postJson('/api/posts', $data);

        // then
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(0, Post::count());
    }

    public function test_인증되지_못한_유저는_포스트를_생성할_수_없음(): void
    {
        // given
        $data = [
            'title' => Str::random(300),
            'content' => 'This is a test content.',
            'author' => Str::random(300),
            'userId' => 1,
        ];

        // when
        $response = $this->postJson('/api/posts', $data);

        // then
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $this->assertEquals(0, Post::count());
    }

    public function test_존재하는_포스트_수정(): void
    {
        // given
        $user = $this->actingAsAuthenticatedUser();
        $post = Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
            'userId' => $user->id,
        ]);
        $postId = $post->id;

        $data = [
            'title' => "수정된 제목",
            'content' => '수정된 내용'
        ];

        // when
        $response = $this->patchJson("/api/posts/{$postId}", $data);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals('수정된 제목', $data['title']);
    }

    public function test_존재하지않는_포스트_수정은_실패(): void
    {
        // given
        $user = $this->actingAsAuthenticatedUser();
        $postId = 999999;
        $data = [
            'title' => '수정된 제목',
            'content' => '수정된 내용',
            'userId' => 1,
        ];

        // when
        $response = $this->patchJson("/api/posts/{$postId}", $data);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_소유자가_다른_포스트는_수정할_수_없다(): void
    {
        // given
        $user = $this->actingAsAuthenticatedUser();
        $post = Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
            'userId' => 3,
        ]);
        $postId = $post->id;

        $data = [
            'title' => "수정된 제목",
            'content' => '수정된 내용'
        ];

        // when
        $response = $this->patchJson("/api/posts/{$postId}", $data);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_포스트_삭제(): void
    {
        // given
        $post = Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
        ]);
        $postId = $post->id;

        // when
        $response = $this->deleteJson("/api/posts/{$postId}");

        // then
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(0, Post::count());
    }
}
