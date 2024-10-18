<?php

declare(strict_types = 1);

namespace App\Infrastructure\Serializer\Handler\Uuid;

use App\Infrastructure\Serializer\Handler\Uuid\Exceptions\DeserializationInvalidValueException;
use App\Infrastructure\Serializer\Handler\Uuid\Exceptions\InvalidUuidException;
use App\Infrastructure\Serializer\Handler\Uuid\Exceptions\NonStringCastableTypeException;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Metadata\PropertyMetadata;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\Uid\Uuid;

class UuidSerializerHandler implements \JMS\Serializer\Handler\SubscribingHandlerInterface
{

    private const PATH_FIELD_SEPARATOR = '.';

    private const TYPE_UUID = 'uuid';

    /**
     * @return string[][]
     */
    public static function getSubscribingMethods(): array
    {
        $formats = [
            'json',
            'xml',
            'yml',
        ];
        $methods = [];
        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => self::TYPE_UUID,
                'format' => $format,
                'method' => 'serializeUuid',
            ];
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'type' => self::TYPE_UUID,
                'format' => $format,
                'method' => 'deserializeUuid',
            ];
        }

        return $methods;
    }

    /**
     * @param DeserializationVisitorInterface $visitor
     * @param $data
     * @param array $type
     * @param Context $context
     * @return Uuid
     * @throws DeserializationInvalidValueException
     * @throws NonStringCastableTypeException
     */
    public function deserializeUuid(DeserializationVisitorInterface $visitor, $data, array $type, Context $context): Uuid // phpcs:ignore
    {
        if (!is_scalar($data) && !$data instanceof \Stringable) {
            throw new NonStringCastableTypeException($data);
        }

        try {
            return $this->deserializeUuidValue((string) $data);
        } catch (InvalidUuidException $e) {
            throw new DeserializationInvalidValueException(
                $this->getFieldPath($context),
                $e
            );
        }
    }

    private function deserializeUuidValue(string $uuidString): Uuid
    {
        if (!Uuid::isValid($uuidString)) {
            throw new InvalidUuidException($uuidString);
        }
        return Uuid::fromString($uuidString);
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param Uuid $uuid
     * @param array $type
     * @return mixed
     */
    public function serializeUuid(SerializationVisitorInterface $visitor, Uuid $uuid, array $type): mixed
    {
        return $visitor->visitString($uuid->toString(), $type);
    }

    private function getFieldPath(Context $context): string
    {
        $path = '';
        foreach ($context->getMetadataStack() as $element) {
            if ($element instanceof PropertyMetadata) {
                $name = ($element->serializedName !== null) ? $element->serializedName : $element->name;

                $path = $name . self::PATH_FIELD_SEPARATOR . $path;
            }
        }
        $path = rtrim($path, self::PATH_FIELD_SEPARATOR);

        return $path;
    }

}

