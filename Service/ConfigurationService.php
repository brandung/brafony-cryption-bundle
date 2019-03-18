<?php
namespace Brafony\CryptBundle\Service;
use ReflectionClass;

/**
 * Class ConfigurationService
 *
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 * @package Brafony\CryptBundle\Service
 */
class ConfigurationService
{
    /**
     * @var array
     */
    private $config;

    /**
     * ConfigurationCheck constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $entityName
     * @return bool
     * @throws \ReflectionException
     */
    public function checkClassNameInConfig(string $entityName) : bool
    {
        $reflectionClass = new ReflectionClass($entityName);
        if(!empty($this->config['entities'])){
            if(array_key_exists(strtolower($reflectionClass->getShortName()),$this->config['entities'])){
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * @param $entityName
     * @return mixed
     * @throws \ReflectionException
     */
    public function getEntityConfig($entityName) : array
    {
        $reflectionClass = new ReflectionClass($entityName);
        if(!empty($this->config['entities'][strtolower($reflectionClass->getShortName())])) {
            return $this->config['entities'][strtolower($reflectionClass->getShortName())];
        }
        return [];
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function hasPrefix($hash)
    {
        if(isset($this->config['service']['rsa']['prefix'])){
            if($hash !== str_replace($this->config['service']['rsa']['prefix'],"",$hash)){
                return true;
            }
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->config['service']['rsa']['prefix'];
    }
}