<?php

namespace Macedigital\Guzzle\Plugin\Serializer;

use Guzzle\Service\Command\LocationVisitor\Request\PostFieldVisitor;
use Guzzle\Service\Description\Parameter;
use JMS\Serializer\SerializerInterface;

/**
 * Serialize request post field
 *
 * @author Matthias Adler <macedigital@gmail.com>
 */
class SerializerPostFieldVisitor extends PostFieldVisitor
{

    protected $serializer;

    /**
     * Inject serializer
     *
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareValue($value, Parameter $param)
    {

        $type = SerializerPlugin::getDeserializerFormat($param->getSentAs());

        if ($type && (is_object($value) || is_array($value))) {
            return $this->serializer->serialize($value, $type);
        }

        return parent::prepareValue($value, $param);

    }
    
}
