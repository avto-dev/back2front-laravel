<?php

namespace AvtoDev\BackendToFrontendVariablesStack\Tests\Unit;

use AvtoDev\BackendToFrontendVariablesStack\Tests\AbstractTestCase;
use AvtoDev\BackendToFrontendVariablesStack\Back2FrontInterface;

/**
 * Helper test to the data transfer service from the back to the front.
 *
 * @group back-to-front
 */
class HelpersTest extends AbstractTestCase
{
    /**
     * Check the type of object returned by the helper.
     */
    public function testBackToFrontStack(): void
    {
        $this->assertInstanceOf(Back2FrontInterface::class, backToFrontStack());
    }
}
