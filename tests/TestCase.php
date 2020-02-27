<?php

namespace Tests;

use Illuminate\Console\Application;
use Illuminate\Contracts\Container\Container;
use MilesChou\Codegener\Writer;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var string
     */
    protected $vfs;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app = $this->createApplication();
        $this->container = $this->app->getLaravel();
        $this->vfs = $this->container->make(vfsStream::class);
    }

    protected function tearDown(): void
    {
        $this->app = null;

        parent::tearDown();
    }

    /**
     * @param string $path
     * @param string $content
     * @return string The temp file path
     */
    protected function prepareFile(string $path, string $content): string
    {
        /** @var Writer $writer */
        $writer = $this->container->make(Writer::class);
        $writer->setBasePath($this->vfs->url())
            ->overwrite($path, $content);

        return $writer->formatPath($path);
    }

    private function createApplication()
    {
        return require dirname(__DIR__) . '/bootstrap/app.php';
    }
}
