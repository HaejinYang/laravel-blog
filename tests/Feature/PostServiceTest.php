<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Requests\PostStoreRequest;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;

    public function test_0개_조회(): void
    {
        // when
        $response = $this->postService->getMany();

        // then
        $this->assertEquals([], $response);
    }

    public function test_N개_조회(): void
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
        $response = $this->postService->getMany();

        // then
        $this->assertCount($postCount, $response);
    }

    public function test_1개_특정해서_조회(): void
    {
        // given
        $post = Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
        ]);

        // when
        $response = $this->postService->getOne($post->id);

        // then
        $this->assertEquals($post->id, $response->getId());
        $this->assertEquals($post->title, $response->getTitle());
        $this->assertEquals($post->content, $response->getContent());
        $this->assertEquals($post->author, $response->getAuthor());
    }

    public function test_포스트_생성(): void
    {
        // given
        $request = new PostStoreRequest(
            '포스트 제목',
            '포스트 내용',
            '포스트 작성자'
        );

        // when
        $response = $this->postService->save($request);

        // then
        $this->assertEquals('포스트 제목', $response->getTitle());
        $this->assertEquals('포스트 내용', $response->getContent());
        $this->assertEquals('포스트 작성자', $response->getAuthor());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->postService = app(PostService::class);
    }
}
