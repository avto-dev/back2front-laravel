<?php

namespace AvtoDev\Back2Front;

use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Traversable;

interface Back2FrontInterface extends Arrayable, Jsonable, Traversable, Countable
{
    /**
     * Add an element to send to the front.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return self|mixed
     */
    public function put($key, $value);

    /**
     * Remove an item by key.
     *
     * @param array|string $keys
     *
     * @return self|mixed
     */
    public function forget($keys);

    /**
     * Get an item by key.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Check that there is data for a given key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);
}
