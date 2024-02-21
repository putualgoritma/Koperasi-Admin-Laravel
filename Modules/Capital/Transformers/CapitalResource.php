<?php

namespace Modules\Capital\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CapitalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
