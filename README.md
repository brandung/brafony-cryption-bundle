###Introduction

#### What is the brafony/cryption-bundle?

The bradony/cryption-bundle is a Symfony-based bundle for de- and enryption. 

#### Installation

Installation is a quick process:

- Download bradony/cryption-bundle using composer


- Enable the Bundle in AppKernel.php
```
public function registerBundles()
{
    $bundles = [
        /*...*/
        new \Brafony\CryptBundle\BrafonyCryptBundle()
    ];
    /*...*/
    return $bundles;
}
```


- Configure your application's parameter.yml

##### Example config for RSA encryption.
```
encryption:
    method: 'RSA'
    service:
        rsa:
            password: password for keys
            publicKey:  'total path for publicKey'
            privateKey: 'total path for privateKey'
            prefix: any prefix
    entities:
        EntityName:
            attribute1: null
            attribute2: null
            attribute3: null
        name:
            EmbeddedEnitiy:
                attribute1: null
                attribute2: null
                attribute3: null
```


#### Commands

Generate Key Pair Command
```
php bin/console brafony:rsa:key:generate
```

#### Allowed Crypto Methods
- RSA

More will come soon 
