<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

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
        $response = $this->get('/posts');

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
        $response = $this->get('/posts');

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
        $response = $this->get("/posts/{$id}");

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
        $response = $this->get("/posts/{$id}");

        // then
        $response->assertStatus(ResponseAlias::HTTP_NOT_FOUND);
        $response->assertJson([
            'code' => ResponseAlias::HTTP_NOT_FOUND,
            'message' => "존재하지 않는 글입니다."
        ]);
    }
}
