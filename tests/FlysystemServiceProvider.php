<?php

namespace WyriHaximus\Pimple\Tests;

use WyriHaximus\Pimple\FlysystemServiceProviderTrait;

class FlysystemServiceProvider
{
    use FlysystemServiceProviderTrait;

    public function registerFlysystemsTest(\Pimple $app)
    {
        $this->registerFlysystems($app);
    }
}
