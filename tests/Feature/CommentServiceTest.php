<?php


use App\Exceptions\CommentNotFound;
use App\Exceptions\PostNotFound;
use App\Models\Comment;
use App\Models\Post;
use App\Requests\Comment\CommentSearchRequest;
use App\Requests\Comment\CommentStoreRequest;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    private CommentService $commentService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commentService = app(CommentService::class);
    }

    public function test_리스트_조회_데이터없으면_응답이_비어야함(): void
    {
        // given
        $request = new CommentSearchRequest();

        // when
        $response = $this->commentService->getMany($request);

        // then
        $this->assertEquals([], $response);
    }

    public function test_리스트_조회_데이터있으면_응답이_있어야함(): void
    {
        // given
        for ($i = 0; $i < 3; $i++) {
            $comment = Comment::create([
                'author' => '테스트 작성자',
                'password' => '1234',
                'content' => '테스트 댓글 내용',
                'post_id' => 1,
            ]);
        }
        $request = new CommentSearchRequest();

        // when
        $response = $this->commentService->getMany($request);

        // then
        $this->assertEquals('테스트 댓글 내용', $response[0]->getContent());
        $this->assertEquals(3, $response[0]->getId());
    }

    public function test_특정_댓글_조회_있는경우(): void
    {
        // given
        $comment = Comment::create([
            'author' => '테스트 작성자',
            'password' => '1234',
            'content' => '테스트 댓글 내용',
            'post_id' => 1,
        ]);

        // when
        $response = $this->commentService->getOne($comment->id);

        // then
        $this->assertEquals('테스트 댓글 내용', $response->getContent());
        $this->assertEquals(1, $response->getId());
    }

    public function test_특정_댓글_조회_없으면_CommentNotFound_예외_발생(): void
    {
        // expected
        $this->assertThrows(function () {
            $this->commentService->getOne(1);
        }, CommentNotFound::class);
    }

    public function test_포스트에_댓글_추가(): void
    {
        // given
        $post = Post::create([
            'title' => '테스트 포스트',
            'content' => '테스트 포스트 내용',
            'author' => '테스트 작성자',
        ]);

        $request = new CommentStoreRequest([
            'author' => '테스트 작성자',
            'content' => '테스트 댓글 내용',
            'password' => '1234',
            'post_id' => $post->id,
        ]);

        // when
        $response = $this->commentService->save($request);

        // then
        $this->assertEquals('테스트 댓글 내용', $response->getContent());
        $this->assertEquals('테스트 작성자', $response->getAuthor());
    }

    public function test_존재하지_않는_포스트에_댓글_추가할수없다(): void
    {
        // given
        $request = new CommentStoreRequest([
            'author' => '테스트 작성자',
            'content' => '테스트 댓글 내용',
            'password' => '1234',
            'post_id' => 99999,
        ]);

        // expected
        $this->assertThrows(function () use ($request) {
            $this->commentService->save($request);
        }, PostNotFound::class);
    }
}
