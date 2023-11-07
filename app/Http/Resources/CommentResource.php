<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'comments_content' => $this->comments_content,
            'created_at' => date_format($this->created_at,'Y-m-d H:i:s'), 
            'user_id' => $this->user_id,
            'comentator' => $this->whenLoaded('comentator'),
        ];
    }
}
