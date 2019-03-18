<?php

namespace Brafony\CryptBundle\Service\Crypto\RSA;

use Brafony\CryptBundle\Service\Crypto\CryptoInterface;
use phpseclib\Crypt\RSA;
use Symfony\Component\Config\Definition\Exception\Exception;


/**
 * Interface CryptoInterface
 *
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 * @package Brafony\CryptBundle\Service
 */
class CryptService implements CryptoInterface
{
    /**
     * @var string
     */
    protected $password;

    /** @var RSA  */
    protected $rsa;

    /** @var array  */
    protected $config;

    /** @var string */
    protected $prefix;


    protected $publicKey = NULL;

    protected $privateKey = NULL;

    /**
     * RSACryptService constructor.
     *
     * Configuration in parameters.yml
     * encryption:
     *      service:
     *          rsa:
     *              password: 'password'
     *              publicKey: 'path/to/public/key'
     *              privateKey: 'path/to/private/key'
     *              prefix: 'prefix' //optional
     * @param array $config
     * @param RSA $rsa
     */
    public function __construct(
        array $config,
        RSA $rsa
    ) {
        $this->password = $config['service']['rsa']['password'];
        $this->prefix   = isset($config['service']['rsa']['prefix']) ?$config['service']['rsa']['prefix']: NULL;
        $this->config   = $config;
        $this->rsa      = $rsa;

        if(file_exists($this->config['service']['rsa']['publicKey']) || file_exists($this->config['service']['rsa']['privateKey'])) {
            $this->publicKey        = file_get_contents($config['service']['rsa']['publicKey']);
            $this->privateKey       = file_get_contents($config['service']['rsa']['privateKey']);
        }

        $this->rsa->setPassword($this->password);

    }

    /**
     * @param $data
     * @return string
     */
    public function encrypt(string $data) : string
    {
        if(!$this->publicKey) {
            throw new Exception('Keys have to be defined');
        }
        $this->rsa->loadKey($this->publicKey);
        $hash = base64_encode($this->rsa->encrypt($data));
        if($this->prefix) {
            return $this->prefix . $hash;
        }
        return $hash;
    }

    /**
     * @param $data
     * @return string
     */
    public function decrypt(string $data) : string
    {
        if(!$this->privateKey) {
            throw new Exception('Keys have to be defined');
        }
        $this->rsa->loadKey($this->privateKey);
        return $this->rsa->decrypt(base64_decode($data));
    }
}