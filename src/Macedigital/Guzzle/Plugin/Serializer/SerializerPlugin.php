<?php

namespace Macedigital\Guzzle\Plugin\Serializer;

use Guzzle\Common\Event;
use Guzzle\Service\Command\OperationCommand;
use JMS\Serializer\Serializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Guzzle plugin using jms-serializer
 *
 * @author Matthias Adler <macedigital@gmail.com>
 */
class SerializerPlugin implements EventSubscriberInterface
{

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var SerializerPostFieldVisitor
     */
    protected $requestPostFieldVisitor;

    /**
     * @var SerializerBodyVisitor
     */
    protected $requestBodyVisitor;

    /**
     * @var SerializerResponseParser
     */
    protected $responseParser;

    /**
     * Register response parser and visitors
     * 
     * @param \JMS\Serializer\Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;

        $this->responseParser = new SerializerResponseParser($this->serializer);
        $this->requestBodyVisitor = new SerializerBodyVisitor($this->serializer);
        $this->requestPostFieldVisitor = new SerializerPostFieldVisitor($this->serializer);
    }

    /**
     * Add jms-serializer guzzle plugin to commands.
     *
     * @param Event $event 
     */
    public function onCommandCreate(Event $event)
    {
        if ($event['command'] instanceof OperationCommand) {
            $event['command']->setResponseParser($this->responseParser);
            $event['command']->getRequestSerializer()->addVisitor('body', $this->requestBodyVisitor);
            $event['command']->getRequestSerializer()->addVisitor('postField', $this->requestPostFieldVisitor);
        }

        if ($event['command'] instanceof SerializerCommandInterface && null !== $this->serializer) {
            $event['command']->setSerializer($this->serializer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'client.command.create' => 'onCommandCreate',
        );
    }

    /**
     * Poormans way of determining supported deserialization types
     *
     * @param string $format
     * @return string|boolean
     */
    public static function getDeserializerFormat($format)
    {

        if (false !== stripos($format, 'json')) {
            return 'json';
        }
        elseif (false !== stripos($format, 'xml')) {
            return 'xml';
        }
        elseif (false !== stripos($format, 'yml') || false !== stripos($format, 'yaml')) {
            return 'yml';
        }

        return false;

    }
}
