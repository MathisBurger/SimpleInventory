<?php

namespace App\Service;

use ArrayObject;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * A middleware service that serializes a doctrine objects
 * into json arrays for displaying data in the frontend.
 */
class SerializingService
{
    private Serializer $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * Serializes an doctrine object to an json array.
     *
     * @param mixed $object The doctrine object that should be serialized
     * @return string|array|ArrayObject|bool|int|float|null The parsed object
     * @throws ExceptionInterface If the serialization failed
     */
    public function normalize(mixed $object): string|array|ArrayObject|bool|int|null|float
    {
        return $this->serializer->normalize($object, null);
    }

    /**
     * Serializes a whole array of doctrine objects.
     *
     * @param array $objects All objects that should be normalized
     * @return array All normalized objects
     * @throws ExceptionInterface If one of the serializations failed
     */
    public function normalizeArray(array $objects): array
    {
        return array_map(function ($object) {
            return $this->normalize($object);
        }, $objects);
    }

}