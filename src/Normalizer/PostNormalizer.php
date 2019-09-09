<?php


namespace App\Normalizer;

use App\Entity\Post;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PostNormalizer implements NormalizerInterface
{

    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $context options that normalizers have access to
     */
    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Post;
    }

    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param mixed $object Object to normalize
     * @param string $format Format the normalization result will be encoded as
     * @param array $context Context options for the normalizer
     *
     * @return array|string|int|float|bool
     *
     * @throws InvalidArgumentException   Occurs when the object given is not an attempted type for the normalizer
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it
     * @throws LogicException             Occurs when the normalizer is not called in an expected context
     * @throws ExceptionInterface         Occurs for all the other cases of errors
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $post = [
            'id' => $object->getId(),
            'body' => $object->getBody(),
            'chatUser' => [
                'id' => $object->getChatUser()->getId(),
                'username' => $object->getChatUser()->getUser()->getUsername(),
                'role' => $object->getChatUser()->getGameRole(),
                'status' => $object->getChatUser()->getGameStatus(),
                ],
            'room' => [
                'id' => $object->getRoom()->getId(),
                'title' => $object->getRoom()->getTitle(),
                'token' => $object->getRoom()->getToken(),
            ],
        ];

        return $post;
    }
}