<?php

namespace Tests;

use MilesChou\Phalog\Sample;
use PHPUnit\Framework\TestCase;

class SampleTest extends TestCase
{
    public function testSample()
    {
        $this->assertTrue((new Sample())->alwaysTrue());
    }
}
