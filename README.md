# 라라벨 블로그

라라벨 12 문서를 참고하여 블로그를 개발한다.

# 개발 내역

## 개발 환경 구축

- 도커 컴포즈(Docker Compose)를 사용해 Nginx, PHP-FPM, MySQL을 구성하여 개발 환경을 구축함.
- 모니터링을 위해 Prometheus, Grafana, Node Exporter, Redis를 도커 컴포즈에 추가함.
- 라라벨 12 버전을 선택하여 프로젝트를 진행함.

## 구현 내용

- 게시글 CRUD
- 댓글 CRUD
- 인증 기능

각각의 기능 구현에 초점을 맞추기보다는 유지보수가 용이한 구조를 고민하면서 개발했다.
특히 다음과 같은 부분에 신경을 썼다.

### 요청 전처리

컨트롤러에서 들어오는 요청을 효과적으로 전처리하는 방법을 고민했다.
서비스 레이어에서는 모든 검증 및 전처리가 끝난 요청을 받아 사용하는 것이 이상적이라고 판단했기 때문이다.

구현 전, 목표한 코드 구조는 다음과 같았다.

```php
public function store(PostStoreFormRequest $formRequest)
{
    $request = $formRequest->toRequest(); // 이 메서드를 호출하면, 검증이 완료된 요청 클래스가 반환됨.
    $post = $this->postService->store($request); // 서비스에서는 FormRequest가 아닌 별도의 요청 클래스를 사용함.

    return new PostResponse($post);
}
```

이를 위해 컨트롤러에서는 `FormRequest`를 사용하고, 서비스 레이어에서는 `FormRequest`를 변환한 요청 전용 클래스를 사용하는 구조를 설계했다.
프로젝트의 `app/Requests` 디렉터리에 `BaseFormRequest`와 `BaseRequest`를 정의하여 사용하고 있다.

- `BaseFormRequest`는 `FormRequest`를 상속받아, 기본 기능을 유지하면서 `toRequest()` 메서드를 추가함.
- `toRequest()`는 요청 데이터를 적절한 요청 DTO로 변환하여 반환함.
- `FormRequest`에서 어떤 요청 DTO로 변환할지 자동으로 결정하는 방법을 고민함. PHP는 C++처럼 제네릭을 지원하지 않기 때문에, 단순한 네이밍 규칙을 적용함.
    - 예: `PostStoreFormRequest` → `PostStoreRequest`

변환 과정에서 리플렉션을 활용하여 `BaseRequest`가 `BaseFormRequest`를 인자로 받아 요청 DTO로 변환하도록 구현했다.
또한, 기본값을 설정할 수 있는 구조를 추가하여 유연성을 높였다.

현재 고민하는 점은 `FormRequest`에서 DTO로 변환하는 로직을 자동화하는 것이다.
거의 모든 변환 로직이 유사하므로, `FormRequest`의 `validated()` 메서드를 오버라이드하는 방식으로 해결할 수 있을지 검토 중이다.

### 응답과 예외처리

응답의 일관성을 유지하는 것이 중요하다고 생각했다.
이를 위해 예외 발생 시에도 일관된 응답을 제공하는 구조를 설계했다.

목표한 코드는 다음과 같다.

```php
public function store(PostStoreFormRequest $formRequest)
{
    $request = $formRequest->toRequest();
    $post = $this->postService->store($request);

    return new PostResponse($post); // Post를 인자로 주어 응답 클래스를 리턴함
}
```

여기서 `PostResponse`는 Model(Post)을 인자로 받아 JSON 응답을 자동으로 생성하는 클래스이다.
이 과정은 비교적 간단했으며, `JsonSerializable` 인터페이스를 구현하여 리플렉션을 활용하는 방식으로 해결했다.

예외 처리는 응답 처리보다 까다로웠다. 라라벨의 기본 예외와 애플리케이션에서 정의한 예외를 구분하여 처리할 필요가 있었다.
이를 해결하기 위해 `Exception`을 상속하는 `BaseException`을 만들고, 애플리케이션의 모든 예외가 이를 상속하도록 설계했다.
또한, `ErrorResponse`라는 전용 에러 응답 클래스를 만들어, 예외 발생 시 항상 이 클래스를 사용하여 응답을 내려주도록 했다.

라라벨의 예외 역시 `Exception`을 상속하고 있었지만, 라라벨 기본 예외와 애플리케이션 예외를 명확하게 구분하기 위해 `BaseException`을 추가했다.

이와 함께, 라라벨의 기본 예외도 `ErrorResponse`로 변환하여 클라이언트에 일관된 형식으로 응답을 제공하도록 구현했다.

### 테스트 코드

유닛 테스트에는 익숙하지 않다. 기존에는 포스트맨(Postman)으로 API를 테스트하거나, 자동화된 테스트를 포스트맨이나 Apache JMeter로 작성하여 사용했다.
이번 기회에 테스트 코드를 작성하며, 테스트 코드의 필요성과 유용성을 직접 경험하고자 했다.

처음에는 테스트 코드 작성이 지루하게 느껴졌다.
게시글과 댓글 기능을 구현할 때는 테스트 코드의 필요성에 대한 의구심이 있었다.

그러나 사용자 인증 기능을 추가하면서 생각이 바뀌었다.
인증과 연동된 코드가 기존 기능에 추가되면서, 처음부터 수작업으로 모든 기능을 다시 테스트하는 것이 막막하게 느껴졌다.

테스트 코드가 있으니, 자동으로 테스트를 실행하고 실패한 부분을 즉시 확인할 수 있었다.
이를 통해 오류가 발생한 부분만 수정하면 되었고, 테스트의 가치를 실감했다.

기존 방식대로 포스트맨을 이용했다면 테스트가 너무 지지부진했을 것이다.
JMeter 같은 자동화 도구를 사용했다면, 문제가 발생한 API는 파악할 수 있었겠지만,
어떤 상황에서 문제가 발생했는지, 어떤 서비스가 호출되었는지를 일일이 분석해야 했을 것이다.

### AI의 활용

이번 프로젝트에서는 AI를 적극적으로 활용했다. 전체적인 구조 설계는 직접 진행했지만, 코드 생성 작업은 AI의 도움을 많이 받았다.

예를 들어, `BaseFormRequest`에서 `BaseRequest`로 변환할 때, 네이밍 규칙을 적용하는 방식을 직접 정의했지만,
이름 비교를 위해 `str_ends_with()` 함수를 호출하는 등의 코드 작성은 AI에게 맡겼다.

이 방식은 예상보다 훨씬 효율적이었다.
리플렉션을 활용한 코드도 개념적인 부분은 직접 설계했지만, 실제 구현 과정에서는 AI의 코드 생성 기능을 적극 활용했다.

최근 AI가 개발자를 대체할 수 있을지에 대한 논의가 활발하다. 완전히 대체될지는 모르겠지만, 프로그래머로서 생존하려면 AI 활용 능력이 점점 더 중요해지고 있음을 실감했다.

# 참고

- [라라벨 12 문서](https://laravel.com/docs/12.x)
- [요청, 응답, 예외처리](https://www.inflearn.com/course/%ED%98%B8%EB%8F%8C%EB%A7%A8-%EC%9A%94%EC%A0%88%EB%B3%B5%ED%86%B5-%EA%B0%9C%EB%B0%9C%EC%87%BC/dashboard)
- [nginx,php-fpm,mysql 세팅](https://www.youtube.com/watch?v=qv-P_rPFw4c)
