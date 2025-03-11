<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_데이터베이스_드라이버_SQLite_memory_확인(): void
    {
        $this->assertEquals('sqlite', config('database.default'));
        $this->assertEquals(':memory:', config('database.connections.sqlite.database'));
    }

    public function test_포스트_1개_조회(): void
    {
        Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
        ]);

        $response = $this->get('/posts');
        $data = $response->json()['data'];

        $response->assertStatus(200);
        $this->assertCount(1, $data);
    }

    public function test_포스트_여러개_조회(): void
    {
        $postCount = 5;
        collect(range(1, $postCount))->each(function ($i) {
            Post::create([
                'title' => '포스트 제목 {$i}',
                'content' => '포스트 내용 {$i}',
                'author' => '포스트 작성자 {$i}',
            ]);
        });

        $response = $this->get('/posts');
        $data = $response->json()['data'];

        $response->assertStatus(200);
        $this->assertCount($postCount, $data);
    }
}
