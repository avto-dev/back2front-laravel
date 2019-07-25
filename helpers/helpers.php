<?php

use AvtoDev\Back2Front\Back2FrontInterface;

if (! \function_exists('backToFrontStack')) {
    /**
     * Get back2front service instance.
     *
     * @return Back2FrontInterface
     */
    function backToFrontStack(): Back2FrontInterface
    {
        return \Illuminate\Container\Container::getInstance()->make(Back2FrontInterface::class);
    }
}
