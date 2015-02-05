<?php

namespace WyriHaximus\Pimple;

use League\Flysystem\Filesystem;

trait FlysystemServiceProviderTrait
{
    /**
     * Register this service provider with the Application.
     *
     * @param \Pimple $app Application.
     *
     * @return void
     */
    protected function registerFlysystem(\Pimple $app)
    {
        $app['flysystem.filesystems'] = array();
        $app['flysystems'] = $app->share(function (\Pimple $app) {
            $flysystems = new \Pimple();
            foreach ($app['flysystem.filesystems'] as $alias => $parameters) {
                $flysystems[$alias] = $this->buildFilesystem($parameters);
            }
            return $flysystems;
        });
    }

    /**
     * Instantiate an adapter and wrap it in a filesystem.
     *
     * @param array $parameters Array containing the adapter classname and arguments that need to be passed into it.
     *
     * @return Filesystem
     */
    protected function buildFilesystem(array $parameters)
    {
        $adapter = new \ReflectionClass($parameters['adapter']);
        return new Filesystem($adapter->newInstanceArgs($parameters['args']));
    }
}
