Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require <package-name> "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new <vendor>\<bundle-name>\<bundle-long-name>(),
        );

        // ...
    }

    // ...
}
```

Step 3: Manage routing
----------------------

The easiest way to enable routing of bundle to your application is using annotations.
Add this code in `config/routing.yml` file of your project:

```php
imports:
    resource: '@IFFChatBundle/Resources/config/routing.yml' 
```

Use Symfony annotations to choose routes of actions:

```php	
/**
 * @Route("/chat")
 */
class ChatController extends Controller
{
    /**
     * @Route("/")
     * 
     * @return Response
     */
    public function indexAction(): Response
    { ... }
```

Step 4: Связь с таблицей пользователей
----------------------

Подробнее о связывании таблиц в Symfony можно почитать в официальной документации: https://symfony.com/doc/current/doctrine/associations.html#content_wrapper
Или рассмотреть пример на сайте ItForFree: http://fkn.ktu10.com/?q=node/9487
Добавьте следующий код в сущность пользователей (@AppBundle/Entity/User):
    
```php
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="IFF\ChatBundle\Entity\Message", mappedBy="userFrom")
     */
    protected $sentMessages;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="IFF\ChatBundle\Entity\Message", mappedBy="userTo")
     */
    protected $receivedMessages;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->sentMessages = new ArrayCollection(); 
        $this->receivedMessages = new ArrayCollection(); 
    }
    
    /**
     * @param Message $sentMessage
     * 
     * @return self
     */
    public function addSentMessages(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) { 
            $sentMessage->setHouse($this); 
            $this->sentMessages->add($sentMessage); 
        }

        return $this;
    }
            
    /**
     * @param Message $sentMessage
     * 
     * @return self
     */
    public function removeSentMessages(Message $sentMessage): self
    {
        if ($this->sentMessages->contains($sentMessage)) { 
            $this->sentMessages->removeElement($sentMessage); 
        }

        return $this;
    }
    
    /**
     * Возвращает список всех юзеров в коллекции
     * 
     * @return ArrayCollection
     */
    public function getSentMessages(): ArrayCollection
    {
        return $this->sentMessages; 
    }
    
    
    
    /**
     * @param Message $recervedMessage
     * 
     * @return self
     */
    public function addReceivedMessages(Message $recervedMessage): self
    {
        if (!$this->recervedMessage->contains($recervedMessage)) { 
            $recervedMessage->setHouse($this); 
            $this->recervedMessage->add($recervedMessage); 
        }

        return $this;
    }
            
    /**
     * @param Message $recervedMessage
     * 
     * @return self
     */
    public function removeReceivedMessages(Message $recervedMessage): self
    {
        if ($this->recervedMessage->contains($recervedMessage)) { 
            $this->recervedMessage->removeElement($recervedMessage); 
        }

        return $this;
    }
    
    /**
     * Возвращает список всех юзеров в коллекции
     * 
     * @return ArrayCollection
     */
    public function getReceivedMessages(): ArrayCollection
    {
        return $this->recervedMessage; 
    }
```

Step 5: JavaScript

1) файл chatMessages.js -- перенести в ваш проект

2) для передачи объектов с private свойствами методом json необходимо подключить 
к вашему классу пользователей интерфейс JsonSerializable и переопределить метод jsonSerialize():
```php
class User extends BaseUser implements JsonSerializable
{
    ... 
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
```