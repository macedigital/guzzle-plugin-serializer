<?php

namespace Macedigital\Guzzle\Plugin\Serializer;

use Guzzle\Http\Message\Response;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Command\DefaultResponseParser;
use Guzzle\Service\Description\OperationInterface;
use JMS\Serializer\SerializerInterface;

/**
 * Deserialize response body
 *
 * @author Matthias Adler <macedigital@gmail.com>
 */
class SerializerResponseParser extends DefaultResponseParser
{

    protected $serializer;

    /**
     * Inject jms-serializer dependency
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
    protected function handleParsing(CommandInterface $command, Response $response, $contentType)
    {

        $body = $response->getBody(true);

        if ($body && OperationInterface::TYPE_CLASS === $command->getOperation()->getResponseType()) {

            $class = $command->getOperation()->getResponseClass();

            if (stripos($contentType, 'json') !== false) {
                return $this->deserialize($body, $class, 'json');
            }
            elseif (stripos($contentType, 'xml') !== false) {
                return $this->deserialize($body, $class, 'xml');
            }
            elseif (stripos($contentType, 'yaml') !== false ) {
                return $this->deserialize($body, $class, 'yaml');
            }

        }

        return parent::handleParsing($command, $response, $contentType);
        
    }

    /**
     * Deserialize string into php object
     *
     * @param string $body String to deserialize
     * @param string $class Fully qualified class name
     * @param string $format Source format (json, xml, yml). Default: json
     * @return object
     */
    protected function deserialize($body, $class, $format = 'json')
    {
        return $this->serializer->deserialize($body, $class, $format);
    }
}
