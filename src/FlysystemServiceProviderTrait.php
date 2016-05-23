<?php

namespace WyriHaximus\Pimple;

use League\Flysystem\Filesystem;
use League\Flysystem\Plugin\EmptyDir;
use League\Flysystem\Plugin\GetWithMetadata;
use League\Flysystem\Plugin\ListFiles;
use League\Flysystem\Plugin\ListPaths;
use League\Flysystem\Plugin\ListWith;

trait FlysystemServiceProviderTrait
{
    /**
     * Register this service provider with the Application.
     *
     * @param \Pimple $app Application.
     *
     * @return void
     */
    protected function registerFlysystems(\Pimple $app)
    {
        $app['flysystem.filesystems'] = [];
        $app['flysystem.plugins'] = [
            new EmptyDir(),
            new GetWithMetadata(),
            new ListFiles(),
            new ListPaths(),
            new ListWith(),
        ];
        $app['flysystems'] = $app->share(function (\Pimple $app) {
            $flysystems = new \Pimple();
            foreach ($app['flysystem.filesystems'] as $alias => $parameters) {
                $flysystems[$alias] = $this->buildFilesystem($app, $parameters);
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
    protected function buildFilesystem(\Pimple $app, array $parameters)
    {
        $adapter = new \ReflectionClass($parameters['adapter']);
        $filesystem = new Filesystem($adapter->newInstanceArgs($parameters['args']), $this->getConfig($parameters));

        foreach ($app['flysystem.plugins'] as $plugin) {
            $plugin->setFilesystem($filesystem);
            $filesystem->addPlugin($plugin);
        }

        return $filesystem;
    }

    protected function getConfig(array $parameters)
    {
        if (isset($parameters['config'])) {
            return $parameters['config'];
        }

        return null;
    }
}
