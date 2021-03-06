<?php
namespace Kemer\Swagger\Jane;

use Joli\Jane\Encoder\RawEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use GuzzleHttp\Client as GuzzleClient;

trait FactoryTrait
{
    /**
     * Generated by Jane NormalizerFactory method whioch returns array with normalizers
     */
    abstract public function create();

    /**
     * {@inheritdoc}
     */
    public function fromUri($class, $baseUri)
    {
        $client = new GuzzleClient(['base_uri' => $baseUri]);
        return $this->factory($class, $client);
    }

    /**
     * {@inheritdoc}
     */
    public function factory($class, GuzzleClient $client)
    {
        return new $class(
            $this->getHttpClient($client),
            $this->getMessageFactory(),
            $this->getSerializer()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpClient(GuzzleClient $client)
    {
        return new GuzzleAdapter($client);
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageFactory($host = null)
    {
        return new MessageFactory($host);
    }

    /**
     * {@inheritdoc}
     */
    public function getSerializer()
    {
        return $serializer = new Serializer(
            $this->getNormalizers(),
            $this->getEncoders()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEncoders()
    {
        return $encoders = [
            new XmlEncoder(),
            new JsonEncoder(
                new JsonEncode(),
                new JsonDecode()
            ),
            new RawEncoder()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getNormalizers()
    {
        return static::create();
    }
}
