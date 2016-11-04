<?php

namespace WyriHaximus\Pimple\Tests;

use Pimple\Container;
use WyriHaximus\Pimple\FlysystemServiceProviderTrait;

class FlysystemServiceProvider
{
    use FlysystemServiceProviderTrait;

    public function registerFlysystemsTest(Container $app)
    {
        $this->registerFlysystems($app);
    }
}
