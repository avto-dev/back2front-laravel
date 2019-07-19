<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front\Tests\Unit;

use AvtoDev\Back2Front\Back2FrontInterface;
use AvtoDev\Back2Front\Tests\AbstractTestCase;

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
