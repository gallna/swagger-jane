<?php
namespace Kemer\Swagger\Jane;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Message\MessageFactory as MessageFactoryInterface;

/**
 * Creates Guzzle messages.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class MessageFactory implements MessageFactoryInterface
{
    private $scheme;

    private $host;

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
    public function createRequest(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        $host = $this->host;
        if (isset($headers["Host"])) {
            $host = $host ?: $headers["Host"];
            unset($headers["Host"]);
        }
        $headers["Content-Type"] = "application/json";
        return new Request(
            $method,
            sprintf("%s://%s%s", $this->scheme, $host, $uri),
            $headers,
            $body,
            $protocolVersion
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse(
        $statusCode = 200,
        $reasonPhrase = null,
        array $headers = [],
        $body = null,
        $protocolVersion = '1.1'
    ) {
        return new Response(
            $statusCode,
            $headers,
            $body,
            $protocolVersion,
            $reasonPhrase
        );
    }
}
