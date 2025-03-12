<?php

namespace Tests\Feature;

use App\Exceptions\PostNotFound;
use App\Models\Post;
use App\Requests\Post\PostSearchRequest;
use App\Requests\Post\PostStoreRequest;
use App\Requests\Post\PostUpdateRequest;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
    use RefreshDatabase;

    private PostService $postService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->postService = app(PostService::class);
    }

    public function test_리스트_조회_데이터없으면_응답이비어야함(): void
    {
        // given
        $request = new PostSearchRequest();

        // when
        $response = $this->postService->getMany($request);

        // then
        $this->assertEquals([], $response);
    }

    public function test_리스트_조회_pageSize이하로_조회되어야함(): void
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
        $pageSize = 3;
        $request = new PostSearchRequest();
        $request->setPageSize($pageSize);

        // when
        $response = $this->postService->getMany($request);

        // then
        $this->assertCount($pageSize, $response);
    }

    public function test_리스트_조회_요청범위가_데이터갯수를_초과하면_응답데이터가_비어야함(): void
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
        $request = new PostSearchRequest();
        $request->setPage(999);
        $request->setPageSize(999);

        // when
        $response = $this->postService->getMany($request);

        // then
        $this->assertCount(0, $response);
    }

    public function test_리스트_조회_오름차순(): void
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
        $request = new PostSearchRequest();
        $request->setOrderBy('asc');

        // when
        $response = $this->postService->getMany($request);

        // then
        $this->assertEquals(1, $response[0]->getId());
    }

    public function test_리스트_조회_내림차순(): void
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
        $request = new PostSearchRequest();
        $request->setOrderBy('desc');

        // when
        $response = $this->postService->getMany($request);

        // then
        $this->assertEquals(5, $response[0]->getId());
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
        $request = new PostStoreRequest([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자'
        ]);

        // when
        $response = $this->postService->save($request);

        // then
        $this->assertEquals('포스트 제목', $response->getTitle());
        $this->assertEquals('포스트 내용', $response->getContent());
        $this->assertEquals('포스트 작성자', $response->getAuthor());
    }

    public function test_존재하는_포스트_수정(): void
    {
        // given
        $post = Post::create([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
            'author' => '포스트 작성자',
        ]);
        $postId = $post->id;

        $request = new PostUpdateRequest([
            'title' => '수정 제목',
            'content' => '수정 내용'
        ]);

        // when
        $response = $this->postService->update($postId, $request);

        // then
        $this->assertEquals('수정 제목', $response->getTitle());
        $this->assertEquals('수정 내용', $response->getContent());
        $this->assertEquals('포스트 작성자', $response->getAuthor());
    }

    public function test_존재하지_않는_포스트_수정은_실패해야함(): void
    {
        // given
        $request = new PostUpdateRequest([
            'title' => '포스트 제목',
            'content' => '포스트 내용',
        ]);
        $postId = 9999999;

        // expected
        $this->assertThrows(function () use ($postId, $request) {
            $this->postService->update($postId, $request);
        }, PostNotFound::class);
    }


}
