<?php

declare(strict_types = 1);

namespace AvtoDev\Back2Front\Tests\Unit;

use AvtoDev\Back2Front\Back2FrontInterface;
use AvtoDev\Back2Front\Tests\AbstractTestCase;

/**
 * @coversNothing
 */
class HelpersTest extends AbstractTestCase
{
    /**
     * @return void
     */
    public function testBackToFrontStack(): void
    {
        $this->assertInstanceOf(Back2FrontInterface::class, backToFrontStack());
    }
}
