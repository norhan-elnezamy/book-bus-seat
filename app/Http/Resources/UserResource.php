<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];

        if($request->route()->getName() == 'api.login'){
            $resource['token'] = 'Bearer '. $this->createToken($this->email)->accessToken;
        }

        return $resource;
    }
}
