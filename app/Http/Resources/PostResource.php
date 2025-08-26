<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
           'id' => $this->id,
           'caption'=>$this->caption,
           'text'=>$this->text,
           'imageUrl'=>$this->when(!is_null($this->getMedia('image')->first()),function (){
              return $this->getMedia('image')->first()->getUrl();
           }),
           'createdAt'=>$this->created_at,
           'updatedAt'=>$this->updated_at
       ];
    }
}
