<?php
namespace Brafony\CryptBundle\EventListener;

use Brafony\CryptBundle\Service\ConfigurationService;
use Brafony\CryptBundle\Service\Crypto\CryptoInterface;
use Brafony\CryptBundle\Service\EncryptionHandleService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\UnitOfWork;


/**
 * Class FlushEventListener
 *
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 * @package Brafony\CryptBundle\EventListener
 */
class FlushEventListener
{
    /**
     * @var ConfigurationService
     */
    private $configurationCheck;

    /**
     * @var EncryptionHandleService
     */
    private $encryptionHandleService;


    /**
     * FlushEventListener constructor.
     *
     * @param ConfigurationService $configurationCheck
     * @param EncryptionHandleService $encryptionHandleService
     */
    public function __construct(
        ConfigurationService    $configurationCheck,
        EncryptionHandleService $encryptionHandleService
    )
    {
        $this->configurationCheck       = $configurationCheck;
        $this->encryptionHandleService  = $encryptionHandleService;
    }

    /**
     * @param OnFlushEventArgs $args
     * @throws \ReflectionException
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em     = $args->getEntityManager();
        $uow    = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if($this->configurationCheck->checkClassNameInConfig(get_class($entity))) {
                $this->encryptionHandleService->cryptoHandling($entity, CryptoInterface::ENCRYPT);
                $this->save($em,$uow,$entity);
            }
        }
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if($this->configurationCheck->checkClassNameInConfig(get_class($entity))) {
                $this->encryptionHandleService->cryptoHandling($entity, CryptoInterface::ENCRYPT);
                $this->save($em,$uow,$entity);
            }
        }
        foreach ($uow->getScheduledCollectionUpdates() as $col) {
            if($this->configurationCheck->checkClassNameInConfig(get_class($col))){
                $this->encryptionHandleService->cryptoHandling($entity, CryptoInterface::ENCRYPT);
                $this->save($em,$uow,$entity);
            }
        }
    }

    /**
     * @param PostFlushEventArgs $args
     * @throws \ReflectionException
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        /** @var EntityManager $em */
        $em     = $args->getEntityManager();
        $uow    = $em->getUnitOfWork();
        foreach ($uow->getIdentityMap() as $entities){
            foreach($entities as $entity){
                if($this->configurationCheck->checkClassNameInConfig(get_class($entity))) {
                    $entity = $this->encryptionHandleService->cryptoHandling($entity, CryptoInterface::DECRYPT);
                    $this->save($em,$uow,$entity);

                }
            }

        }
    }

    /**
     * @param EntityManager $em
     * @param UnitOfWork $uow
     * @param $entity
     */
    public function save(EntityManager $em,UnitOfWork $uow,$entity)
    {
        $metaData = $em->getClassMetadata(get_class($entity));
        $uow->recomputeSingleEntityChangeSet($metaData, $entity);
    }

}