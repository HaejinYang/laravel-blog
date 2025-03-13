<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_회원가입_성공(): void
    {
        // given
        $request = [
            'email' => 'haejin@gmail.com',
            'password' => '12345678',
            "name" => "haejin"
        ];

        // when
        $response = $this->postJson("/api/auth/register", $request);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals("haejin@gmail.com", $data['email']);
        $this->assertEquals("haejin", $data['name']);
    }

    public function test_회원가입_패스워드가_짧으면_실패(): void
    {
        // given
        $request = [
            'email' => 'haejin@gmail.com',
            'password' => '1',
            "name" => "haejin"
        ];

        // when
        $response = $this->postJson("/api/auth/register", $request);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_회원가입_패스워드가_길면_실패(): void
    {
        // given
        $request = [
            'email' => 'haejin@gmail.com',
            'password' => Str::random(256),
            "name" => "haejin"
        ];

        // when
        $response = $this->postJson("/api/auth/register", $request);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_회원가입_이메일양식이_아니면_실패(): void
    {
        // given
        $request = [
            'email' => 'haejingmail.com',
            'password' => Str::random(256),
            "name" => "haejin"
        ];

        // when
        $response = $this->postJson("/api/auth/register", $request);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_유저가있으면_로그인(): void
    {
        // given
        $email = 'haejin@gmail.com';
        $password = '12345678';
        User::create([
            'email' => $email,
            'password' => $password,
            'name' => 'haejin'
        ]);
        $request = [
            'email' => $email,
            'password' => $password
        ];

        // when
        $response = $this->postJson("/api/auth/login", $request);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_OK);
        $this->assertArrayHasKey('token', $data);
        $this->assertEquals(1, $data['id']);
        $this->assertEquals($email, $data['email']);
    }

    public function test_유저가_없으면_로그인_실패(): void
    {
        // given
        $email = 'haejin@gmail.com';
        $password = '12345678';
        $request = [
            'email' => $email,
            'password' => $password
        ];

        // when
        $response = $this->postJson("/api/auth/login", $request);

        // then
        $data = $response->json();
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_인증정보가_모두_맞으면_내_정보_보여줌(): void
    {
        // given
        $email = 'haejin@gmail.com';
        $password = '12345678';
        User::create([
            'email' => $email,
            'password' => $password,
            'name' => 'haejin'
        ]);
        $request = [
            'email' => $email,
            'password' => $password
        ];

        // when
        $response = $this->postJson("/api/auth/login", $request);
        $data = $response->json();
        $this->assertArrayHasKey('token', $data);

        $token = $data['token'];
        $response = $this->getJson("/api/auth/me", [
            'Authorization' => "Bearer {$token}"
        ]);

        // then
        $response->assertStatus(Response::HTTP_OK);
        $data = $response->json();
        $this->assertEquals(1, $data['id']);
        $this->assertEquals($email, $data['email']);
        $this->assertEquals('haejin', $data['name']);
    }

    public function test_토큰이_틀리면_내_정보_보여주지_않음(): void
    {
        // given
        $email = 'haejin@gmail.com';
        $password = '12345678';
        User::create([
            'email' => $email,
            'password' => $password,
            'name' => 'haejin'
        ]);
        $request = [
            'email' => $email,
            'password' => $password
        ];

        // when
        $response = $this->postJson("/api/auth/login", $request);
        $data = $response->json();
        $this->assertArrayHasKey('token', $data);

        $token = $data['token'] . '1';
        $response = $this->getJson("/api/auth/me", [
            'Authorization' => "Bearer {$token}"
        ]);

        // then
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
