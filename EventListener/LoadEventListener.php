<?php

namespace Brafony\CryptBundle\EventListener;

use Brafony\CryptBundle\Service\ConfigurationService;
use Brafony\CryptBundle\Service\Crypto\CryptoInterface;
use Brafony\CryptBundle\Service\EncryptionHandleService;
use Doctrine\ORM\Event\LifecycleEventArgs;


/**'
 * Class FlushEventListener
 *
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 * @package Brafony\CryptBundle\EventListener
 */
class LoadEventListener
{
    /**
     * @var EncryptionHandleService
     */
    private $encryptionHandleService;

    /**
     * @var ConfigurationService
     */
    private $configurationService;

    /**
     * LoadEventListener constructor.
     * @param EncryptionHandleService $encryptionHandleService
     * @param ConfigurationService $configurationService
     */
    public function __construct(
        EncryptionHandleService $encryptionHandleService,
        ConfigurationService    $configurationService
    )
    {
        $this->encryptionHandleService  = $encryptionHandleService;
        $this->configurationService     = $configurationService;
    }

    /**
     * @param LifecycleEventArgs $args
     * @return mixed|object
     * @throws \ReflectionException
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if(is_object($entity)){
            if ($this->configurationService->checkClassNameInConfig(get_class($entity))) {
                $entity = $this->encryptionHandleService->cryptoHandling($entity, CryptoInterface::DECRYPT);
            }
        }
        return $entity;
    }
}