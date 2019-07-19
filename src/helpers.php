<?php

use AvtoDev\BackendToFrontendVariablesStack\Back2FrontInterface;

if (! \function_exists('backToFrontStack')) {
    /**
     * Get back2front service instance.
     *
     * @return Back2FrontInterface
     */
    function backToFrontStack()
    {
        return \Illuminate\Container\Container::getInstance()->make(Back2FrontInterface::class);
    }
}
