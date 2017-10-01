<?php

namespace App\Api\Transformers;

use App\Models\Location;
use League\Fractal\TransformerAbstract;

class LocationTransformer extends TransformerAbstract
{
    public function transform()
    {
        return [
            'id' => $location->parent_id,
        ];
    }
}
