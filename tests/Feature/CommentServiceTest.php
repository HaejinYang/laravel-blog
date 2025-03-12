<?php


use App\Exceptions\CommentNotFound;
use App\Models\Comment;
use App\Requests\Comment\CommentSearchRequest;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    private CommentService $commentService;

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

    protected function setUp(): void
    {
        parent::setUp();

        $this->commentService = app(CommentService::class);
    }
}
