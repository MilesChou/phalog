<?php

namespace Tests\Phalog\View;

use Illuminate\View\Factory as View;
use Tests\TestCase;

class EnginesTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCompileBladeAndMarkdown(): void
    {
        $content = <<<MARKDOWN
---
title: This is title
---

## H2 {{ \$title }}

### H3
MARKDOWN;

        $expected = <<<HTML
<h2>H2 test</h2>
<h3>H3</h3>
HTML;


        $path = $this->prepareFile('test.blade.md', $content);

        /** @var View $target */
        $target = $this->container->make(View::class);

        $this->assertSame($expected, $target->file($path, ['title' => 'test'])->render());
    }

    /**
     * @test
     */
    public function shouldCompileMarkdown(): void
    {
        $content = <<<MARKDOWN
---
title: This is title
---

## H2

### H3
MARKDOWN;

        $expected = <<<HTML
<h2>H2</h2>
<h3>H3</h3>
HTML;


        $path = $this->prepareFile('test.md', $content);

        /** @var View $target */
        $target = $this->container->make(View::class);

        $this->assertSame($expected, $target->file($path)->render());
    }
}
