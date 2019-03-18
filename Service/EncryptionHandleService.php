<?php

namespace Brafony\CryptBundle\Service;

use Brafony\CryptBundle\Service\Crypto\CryptoInterface;
use Brafony\CryptBundle\Service\Crypto\RSA\CryptService;

/**
 * Class EncryptionHandleService
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 * @package Brafony\CryptBundle\Service
 */
class EncryptionHandleService
{

    /**
     * @var ConfigurationService
     */
    private $configurationService;

    /**
     * @var HandleEntityService
     */
    private $entityService;

    /**
     * @var CryptoInterface
     */
    private $crypt;

    /**
     * EncryptionHandleService constructor.
     * @param ConfigurationService $configurationService
     * @param HandleEntityService $entityService
     * @param CryptoInterface $crypt
     */
    public function __construct(
        ConfigurationService $configurationService,
        HandleEntityService $entityService,
        CryptoInterface $crypt
    )
    {
        $this->configurationService = $configurationService;
        $this->entityService        = $entityService;
        $this->crypt                = $crypt;
    }
    /**
     * @param $entity
     * @param $method
     * @return mixed
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function cryptoHandling($entity,string $method)
    {
        if ($this->configurationService->checkClassNameInConfig(get_class($entity))) {
            $config = $this->configurationService->getEntityConfig($entity);
            foreach ($config as $key => $value) {
                if ($value === null) {
                    $entity = $this->cryptValue($entity, $key, $method);
                } else {
                    $getter = $this->entityService->generateGetterName($key);
                    $this->entityService->methodExistCheck($entity, $getter);
                    $obj = $entity->$getter();
                    foreach ($value as $attrKey => $attr) {
                        $obj = $this->cryptValue($obj, $attrKey, $method);
                    }
                    $setter = $this->entityService->generateSetterName($key);
                    $this->entityService->methodExistCheck($entity, $setter);
                    $entity->$setter($obj);
                }
            }
        }
        return $entity;
    }


    /**
     * @param $entity
     * @param $attr
     * @param $method
     * @return mixed
     * @throws \Exception
     */
    public function cryptValue($entity,string $attr,string $method)
    {
        $getter = $this->entityService->generateGetterName($attr);
        $this->entityService->methodExistCheck($entity,$getter);
        $value = $entity->$getter();

        $setter = $this->entityService->generateSetterName($attr);
        $this->entityService->methodExistCheck($entity,$setter);

        switch($method) {
            case CryptService::ENCRYPT:
                if($value === str_replace($this->configurationService->getPrefix(),"",$value)){
                    $entity->$setter($this->crypt->encrypt($value));
                }
                break;
            case CryptService::DECRYPT:
                if($value !== Null){
                    if($this->configurationService->hasPrefix($value)){
                        $value = str_replace($this->configurationService->getPrefix(),"",$value);
                        $entity->$setter($this->crypt->decrypt($value));
                    }
                }
                break;
        }

        return $entity;
    }
}