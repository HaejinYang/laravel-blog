<?php


use App\Exceptions\InvalidLogin;
use App\Models\User;
use App\Requests\Auth\AuthLoginRequest;
use App\Requests\Auth\AuthRegisterRequest;
use App\Services\AuthService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    public function test_회원가입_성공(): void
    {
        // given
        $request = new AuthRegisterRequest([
            'email' => 'haejin@gmail.com',
            'password' => '12345678',
            'name' => 'haejin'
        ]);

        // when
        $response = $this->authService->register($request);

        // then
        $this->assertEquals(1, $response->getId());
        $this->assertEquals('haejin@gmail.com', $response->getEmail());
        $this->assertEquals('haejin', $response->getName());
        $this->assertEquals(1, User::count());
    }

    public function test_중복_이메일은_회원가입_할수없음(): void
    {
        // given
        User::create([
            'email' => 'haejin@gmail.com',
            'password' => '12345678',
            'name' => 'haejin'
        ]);

        $request = new AuthRegisterRequest([
            'email' => 'haejin@gmail.com',
            'password' => '12345678',
            'name' => 'haejin'
        ]);

        // expected
        $this->assertThrows(function () use ($request) {
            $this->authService->register($request);
        }, UniqueConstraintViolationException::class);
    }

    public function test_인증_정보_맞으면_로그인_통과(): void
    {
        // given
        User::create([
            'email' => 'haejin@gmail.com',
            'password' => '12345678',
            'name' => 'haejin'
        ]);
        $request = new AuthLoginRequest([
            'email' => 'haejin@gmail.com',
            'password' => '12345678',
        ]);

        // when
        $response = $this->authService->login($request);
        $this->assertEquals('haejin@gmail.com', $response->getEmail());
        $this->assertNotNull($response->getToken());
    }

    public function test_인증_정보_다르면_로그인_실패(): void
    {
        // given
        User::create([
            'email' => 'haejin@gmail.com',
            'password' => '12345678',
            'name' => 'haejin'
        ]);
        $request = new AuthLoginRequest([
            'email' => 'haejin@gmail.com',
            'password' => '123456781',
        ]);

        // when
        $this->assertThrows(function () use ($request) {
            $this->authService->login($request);
        }, InvalidLogin::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = app(AuthService::class);
    }
}
