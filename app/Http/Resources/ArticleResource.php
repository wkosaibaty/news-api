<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "title" => $this->title,
            "content" => $this->content,
            "image_url" => $this->image_url,
            "source" => SourceResource::make($this->source),
            "category" => CategoryResource::make($this->category),
            "author" => AuthorResource::make($this->author),
            "published_at" => $this->published_at
        ];
    }
}
