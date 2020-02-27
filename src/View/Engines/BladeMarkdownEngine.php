<?php

namespace MilesChou\Phalog\View\Engines;

use Illuminate\Contracts\View\Engine;
use MilesChou\Parkdown\Parser as Parkdown;

class BladeMarkdownEngine implements Engine
{
    /**
     * @var Engine
     */
    private $blade;

    /**
     * @var Parkdown
     */
    private $parkdown;

    public function __construct(Engine $blade, Parkdown $parkdown)
    {
        $this->blade = $blade;
        $this->parkdown = $parkdown;
    }

    /**
     * @param string $path
     * @param array<mixed> $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        $content = $this->blade->get($path, $data);

        return $this->parkdown->parse($content)->html();
    }
}
