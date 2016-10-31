<?php

namespace WyriHaximus\Pimple;

use Pimple\Container;
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
     * @param Container $app Application.
     *
     * @return void
     */
    protected function registerFlysystems(Container $app)
    {
        $app['flysystem.filesystems'] = [];
        $app['flysystem.plugins'] = [
            new EmptyDir(),
            new GetWithMetadata(),
            new ListFiles(),
            new ListPaths(),
            new ListWith(),
        ];
        $app['flysystems'] = function (Container $app) {
            $flysystems = new Container();
            foreach ($app['flysystem.filesystems'] as $alias => $parameters) {
                $flysystems[$alias] = $this->buildFilesystem($app, $parameters);
            }
            return $flysystems;
        };
    }

    /**
     * Instantiate an adapter and wrap it in a filesystem.
     *
     * @param array $parameters Array containing the adapter classname and arguments that need to be passed into it.
     *
     * @return Filesystem
     */
    protected function buildFilesystem(Container $app, array $parameters)
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
