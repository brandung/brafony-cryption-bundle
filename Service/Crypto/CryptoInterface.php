<?php

namespace Brafony\CryptBundle\Service\Crypto;


/**
 * Interface CryptoInterface
 *
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 * @package Brafony\CryptBundle\Service
 */
interface CryptoInterface
{
    /** @var string  */
    const DECRYPT = 'decrypt';

    /** @var string  */
    const ENCRYPT = 'encrypt';

    /**
     * @param string $data
     * @return string
     */
    public function encrypt(string $data) : string ;

    /**
     * @param string $data
     * @return string
     */
    public function decrypt(string $data) : string;
}