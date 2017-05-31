<?php

namespace WyriHaximus\Pimple\Tests;

use Pimple\Container;

class FlysystemServiceProviderTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterFlysystems()
    {
        $pimple = new Container();
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
            'local2' => [
                'adapter' => 'League\Flysystem\Adapter\Local',
                'args' => [
                    __DIR__,
                ],
            ],
        ];

        $this->assertInstanceOf('League\Flysystem\Filesystem', $pimple['flysystems']['local']);
        $this->assertTrue($pimple['flysystems']['local']->getConfig()->has('foo'));
        $this->assertSame('bar', $pimple['flysystems']['local']->getConfig()->get('foo'));

        $this->assertInstanceOf('League\Flysystem\MountManager', $pimple['flysystem.mount_manager']);

        $this->assertInstanceOf('League\Flysystem\Filesystem', $pimple['flysystem.mount_manager']->getFilesystem('local'));
        $this->assertInstanceOf('League\Flysystem\Filesystem', $pimple['flysystem.mount_manager']->getFilesystem('local2'));

        $pimple['flysystem.mount_manager']->mountFilesystem('local', $pimple['flysystems']['local']);

        $this->assertTrue($pimple['flysystem.mount_manager']->has('local://' . basename(__FILE__)));
    }
}
