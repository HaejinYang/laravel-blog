# 라라벨 블로그

라라벨 12 문서를 보면서, 블로그를 만든다.

# 1. 라라벨 설치하기

php, composer 등 설치 방법 생략

```bash
composer global require laravel/installer
laravel new laravel-blog
```

# 2. 개발환경 구축

도커 기반으로 개발 환경 구축. nginx, php-fpm, mysql
[참고링크](https://www.youtube.com/watch?v=qv-P_rPFw4c)

# 3. 블로그 기능 추가

블로그 기능 추가하면서, 요청, 응답, 예외 처리도 같이

## 1) 포스트 조회

포스트 리소스 컨트롤러 생성 `php artisan make:controller PostController --resource`

포스트 모델과 마이그레이션 생성 `php artisan make:model Post --migration`

포스트 응답클래스 생성 `php artisan make:resource PostResource`

포스트 테스트 생성 `php artisan make:test PostTest`

테스트를 위해 .env.testing 사용. php artisan test가 .env.testing을 먼저 인식함.

테스트엔 RefreshDatabase 트레잇을 사용하고(매 테스트마다 디비를 리셋함)
속도를 위해 테스트 디비로 sqlite의 :memory:를사용

## 2) 응답 및 예외

공용으로 사용할 예외, 에러 응답 상위 클래스 추가하고 적용.

## 3) 포스트 서비스 추가하고 글 조회 API에 새로운 응답과 예외 추가함.

## 4)  
