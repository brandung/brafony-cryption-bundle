<?php

namespace Brafony\CryptBundle\Command;


use phpseclib\Crypt\RSA;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author  Oliver Kossin <oliver.kossin@brandung.de>
 */
class KeyGenerateCommand extends ContainerAwareCommand
{

    /**
     * @var RSA
     */
    public $rsa;

    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('brafony:rsa:key:generate');
        $this->rsa = new RSA();
    }

    /**
     * {@inheritdoc}
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getContainer()->getParameter('encryption');
        if(!$config['service']['rsa']){
            $output->writeln('<error>Define the config.yml</error>');
        }
        if(isset($config['service']['rsa']['password'])){
            $this->rsa->setPassword($config['service']['rsa']['password']);
        }
        $keys = $this->rsa->createKey();
        $pub = fopen($config['service']['rsa']['publicKey'], "w");
        fwrite($pub, $keys['publickey']);
        fclose($pub);
        $priv = fopen($config['service']['rsa']['privateKey'], "w");
        fwrite($priv, $keys['privatekey']);
        fclose($priv);

    }
}
