<?php

namespace WyriHaximus\Pimple\Tests;

class FlysystemServiceProviderTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterFlysystems()
    {
        $pimple = new \Pimple();
        (new FlysystemServiceProvider())->registerFlysystemsTest($pimple);
        $pimple['flysystem.filesystems'] = [
            'local' => [
                'adapter' => 'League\Flysystem\Adapter\Local',
                'args' => [
                    __DIR__,
                ],
                'config' => [
                    'foo' => 'bar',
                ],
            ],
        ];
        $this->assertInstanceOf('League\Flysystem\Filesystem', $pimple['flysystems']['local']);
        $this->assertTrue($pimple['flysystems']['local']->getConfig()->has('foo'));
        $this->assertSame('bar', $pimple['flysystems']['local']->getConfig()->get('foo'));
    }
}
