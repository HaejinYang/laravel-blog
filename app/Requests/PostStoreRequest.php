<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostStoreRequest
{
    public function __construct(
        public string $title,
        public string $content,
        public string $author
    )
    {
    }

    public static function fromArray(FormRequest $formRequest): self
    {
        $data = $formRequest->validated();

        return new self(
            $data['title'],
            $data['content'],
            $data['author']
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
        ];
    }
}
