<?php
namespace Brafony\CryptBundle\Service;
use Exception;

/**
 * Class HandleEntityService
 *
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 * @package Brafony\CryptBundle\Service
 */
class HandleEntityService
{
    /**
     * @param $attribute
     * @return string
     */
    public function generateGetterName($attribute) : string
    {
        return 'get'.ucfirst($attribute);
    }

    /**
     * @param $attribute
     * @return string
     */
    public function generateSetterName($attribute) : string
    {
        return 'set'.ucfirst($attribute);
    }

    /**
     * @param $entity
     * @param string $method
     * @throws Exception
     */
    public function methodExistCheck($entity,string $method)
    {
        if(!method_exists($entity,$method)){
            throw new Exception('Method "'.$method.'" doesnt exist');
        }
    }
}