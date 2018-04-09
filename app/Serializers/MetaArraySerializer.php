<?php

namespace App\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class MetaArraySerializer extends ArraySerializer
{

    protected $total = null;

    /**
     * Serialize a collection.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function collection($resourceKey, array $data)
    {
        return array('data' => $data);
    }

    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        return array('data' => $data);
    }

    public function meta(Array $meta)
    {
        return $meta;
    }
}
