<?php

namespace Brafony\CryptBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class BrafonyCryptExtension
 * @package Brafony\CryptBundle\DependencyInjection
 */
class BrafonyCryptExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('events.yml');
        $loader->load('bundleConfig.yml');

        $encryptionConfig = $container->getParameter('encryption');
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/crypto'));
        if(file_exists(__DIR__.'/../Resources/config/crypto/'.strtolower($encryptionConfig['method']).'.yml')){
            $loader->load(strtolower($encryptionConfig['method']).'.yml');
        } else {
            throw new \Exception($encryptionConfig['method'].'is an illegal crypto method');
        }
    }
}
