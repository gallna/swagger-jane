<?php
namespace Kemer\Swagger\Jane;

use Symfony\Component\Serializer\Serializer;
use Joli\Jane\Encoder\RawEncoder;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use Http\Client\HttpClient;
use Http\Client\Plugin\ContentLengthPlugin;
use Http\Client\Plugin\DecoderPlugin;
use Http\Client\Plugin\ErrorPlugin;
use Http\Client\Plugin\PluginClient;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Client\Socket\Client as SocketHttpClient;
use Http\Client\Curl\Client as CurlHttpClient;
use Http\Message\MessageFactory\DiactorosMessageFactory;
use Http\Message\StreamFactory\DiactorosStreamFactory;
use Psr\Http\Message\RequestInterface;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

/**
 * Creates Guzzle messages.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractFactory
{
    protected $scheme;

    protected $host;

    /**
     * {@inheritdoc}
     */
    public static function create($class, $host = null, $scheme = "http")
    {
        return (new static($host, $scheme))->factory($class);
    }

    /**
     * @param string $host
     * @param string $scheme
     */
    public function __construct($host = null, $scheme = "http")
    {
        $this->host = $host;
        $this->scheme = $scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function factory($class)
    {
        return new $class(
            $this->getHttpClient(),
            $this->getMessageFactory(),
            $this->getSerializer()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getHttpClient()
    {
        return new GuzzleAdapter();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageFactory()
    {
        return new MessageFactory($this->host, $this->scheme);
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
    abstract public function getNormalizers();
}
