<?php

namespace Macedigital\Guzzle\Plugin\Serializer;

use Guzzle\Service\Command\CommandInterface;
use JMS\Serializer\SerializerInterface;

/**
 * SerializerCommandInterface
 *
 * @author Matthias Adler <macedigital@gmail.com>
 */
interface SerializerCommandInterface extends CommandInterface
{

    /**
     * @param \JMS\Serializer\SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer);

    /**
     * @return \JMS\Serializer\SerializerInterface
     */
    public function getSerializer();
}
