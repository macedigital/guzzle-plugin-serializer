<?php

namespace Macedigital\Guzzle\Plugin\Serializer;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Mimetypes;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Command\LocationVisitor\Request\BodyVisitor;
use Guzzle\Service\Description\Parameter;
use JMS\Serializer\SerializerInterface;

/**
 * Serialize post body
 *
 * @author Matthias Adler <macedigital@gmail.com>
 */
class SerializerBodyVisitor extends BodyVisitor
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
    public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value)
    {

        if (is_object($value) || is_array($value)) { 
            
            $type = SerializerPlugin::getDeserializerFormat($param->getSentAs());
            
            if ($type) {

                $mimeTypes = Mimetypes::getInstance();
                $request->setHeader('Content-Type', $mimeTypes->fromExtension($type));
                $value = $this->serializer->serialize($value, $type);

            }

        }

        parent::visit($command, $request, $param, $value);
    }
}
