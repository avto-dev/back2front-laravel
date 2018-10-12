<?php

use AvtoDev\BackendToFrontendVariablesStack\Contracts\BackendToFrontendVariablesInterface;

if (! function_exists('backToFrontStack')) {
    /**
     * Get back2front service instance.
     *
     * @return BackendToFrontendVariablesInterface
     */
    function backToFrontStack()
    {
        return resolve(BackendToFrontendVariablesInterface::class);
    }
}
