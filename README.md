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

- 포스트 리소스 컨트롤러 생성 `php artisan make:controller PostController --resource`

- 포스트 모델과 마이그레이션 생성 `php artisan make:model Post --migration`

- 포스트 응답클래스 생성 `php artisan make:resource PostResource`

- 포스트 테스트 생성 `php artisan make:test PostTest`

- 테스트를 위해 .env.testing 사용. php artisan test가 .env.testing을 먼저 인식함.

- 테스트엔 RefreshDatabase 트레잇을 사용하고(매 테스트마다 디비를 리셋함)
  속도를 위해 테스트 디비로 sqlite의 :memory:를사용

## 2) 응답 및 예외

- 공용으로 사용할 예외, 에러 응답 상위 클래스 추가하고 적용.

## 3) 포스트 서비스 추가하고 글 조회 API에 새로운 응답과 예외 추가함.

## 4) BaseResponse 추가하여 JSON 직렬화 자동화

- BaseResponse 클래스 생성 및 JsonSerializable 구현
- Reflection을 활용하여 객체 속성을 자동으로 직렬화하도록 처리
- 기존 응답 클래스를 BaseResponse를 상속하도록 변경
- 중복된 jsonSerialize 구현 코드 제거

## 5) web route에서 api route로 교체

- Sanctum이 같이 설치되었는데, 일단은 사용안함
- 경로가 /posts -> /api/posts로 일괄적으로 /api가 붙게됨

## 6) 포스트 생성 기능 추가

- 포스트 생성 API 및 서비스 추가
- 요청 인자 검증 후 타입 재지정을 위한 PostStoreRequest 추가

## 7) BaseRequest 도입하여 요청 클래스 중복 코드 제거

- PostStoreRequest와 유사한 Request 클래스에서 반복될 변환 로직을 BaseRequest로 추출
- 공통 변환 로직을 BaseRequest에서 처리하여 코드 중복 제거
- 모든 Request 클래스가 BaseRequest를 상속하도록 변경하여 일관성 유지

## 8) FormRequest 검증 실패 시 커스텀 ErrorResponse 반환

- 기본 422 응답 대신 앱에서 정의한 ErrorResponse 형식으로 검증 실패 응답 처리
- bootstrap.app에서 withExceptions에 정의함.
- 일관된 에러 응답 포맷을 유지

## 9) AppException 이름을 BaseException으로 변경

프로젝트에서 사용하는 최상위 클래스들은 이름이 모두 Base... 로 시작한다.

## 10) FormRequest에서 DTO 변환 로직 자동화

- BaseFormRequest에서 DTO 클래스를 자동으로 유추하도록 개선
- 기존 `toDto()` 메서드에서 별도 DTO 클래스를 지정할 필요 없음
- 네이밍 패턴을 기반으로 DTO 클래스를 감지하도록 구현

## 11) BaseRequest 클래스에 기본값 적용 가능하도록 변경

FormRequest 클래스를 Request 클래스로 변환할 때, 요청으로 전달되지 않은 인자에 대한 기본값을 설정할 필요가 있음.
BaseRequest 클래스를 상속받는 클래스에서 기본값을 적용할 수 있는 구조를 추가함.

## 12) 포스트 리스트 조회에 페이지네이션 적용

EloquentOrm의 `Model::pageinate()` 메서드를 사용하여 페이지네이션을 적용함.
페이지 번호, 페이지 크기, 오름차순&내림차순 정렬을 적용함.

## 13) 댓글 모델 정의하고 댓글 조회 API 추가

- 댓글 모델과 마이그레이션 생성
- 댓글 리소스 컨트롤러 생성
- 댓글 조회 API 추가
- 댓글 조회 API 테스트 추가. assertThrow를 활용하여 CommentService의 getOne 메서드 예외 처리 테스트

## 14) 포스트 수정 API 추가

- 기존 포스트를 찾고, 찾지 못하면 PostNotFound 예외를 던짐
- 찾으면, 업데이트하고, save하고, 응답함

# 4. 중간 점검

- 블로그 포스트 리스트 조회, 조회, 생성 API 및 테스트
- 요청 처리를 위해 FormRequest와 BaseRequest 클래스 적용
- 응답 처리를 위해 BaseResponse 클래스 적용
- 예외 처리를 위해 BaseException와 ErroResponse 클래스 적용

## 15) Post 관련 FormRequest, Request 따로 분리. Comment도 마찬가지로

## 16) 모델 프로퍼티 이름을 카멜 케이스로 인식해주는 Attribute 추가

Comment 모델에서 포스트 id를 post_id로 갖고 있음. 이로 인하여 FormRequest, Request 등
여러 관련 클래스의 프로퍼티 이름이 post_id만 스네이크 케이스고, 나머지는 카멜 케이스임.
이를 해결하기 위해, 모델의 프로퍼티 이름을 카멜 케이스로 인식해주는 Attribute를 추가함.
Comment 모델 참고.

## 17) PasswordEncryptor 추가 및 Comment 모델 비밀번호 처리 개선

- PasswordEncryptor 서비스 추가 (비밀번호 암호화 전담)
- Comment 모델에 `setPasswordAttribute` Mutator를 사용하여 password 자동 해싱 적용
- Comment 수정 및 삭제 시, 입력된 password가 기존 hash와 일치하는지 검증 로직 추가

## 18) Sanctum을 이용한 인증 API 추가

- Sanctum을 이용하여 가입, 로그인, 로그아웃 같은 인증 API 추가
- 토큰 발행 및 검증 진행

## 19) 포스트 컨트롤러 인증 적용 및 유저 연동

- 포스트 컨트롤러에 `auth:sanctum` 미들웨어 적용
- 포스트 모델과 포스트 생성 API에 유저 연동 (`user_id` 추가)
- 테스트 코드에서 인증된 유저를 제공하는 `AuthHelper` 트레잇 추가

## 20) 댓글 컨트롤러 인증 적용 및 유저 연동

## 21) 인증이 필요한 FormRequest에서 AuthenticatedFormRequest를 상속하도록 리팩토링

인증이 필요한 FormRequest에 validated method를 오버라이딩하여 userId를 추가했음.
코드 중복 제거를 위해, 이를 AuthenticatedFormRequest로 분리하여, 인증이 필요한 FormRequest는 이 클래스를 상속하도록 변경함.

해야할일

- ~~validated 중복 코드 제거 방법~~

# 알아볼거

- phpunit에서 컨트롤러 호출하는 원리
- phpunit에서 use RefreshDatabase; 좀더 자세히
- php의 static late binding과 reflection
- API 문서 생성
- 암호화 알고리즘, 해시, 내가 쓴 Hash::make의 기본 알고리즘?
- use HasApiTokens, HasFactory, Notifiable;
