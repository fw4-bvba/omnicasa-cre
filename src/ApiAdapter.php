<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE;

use OmnicasaCRE\Request\Request;
use OmnicasaCRE\Response\ResponseObject;
use OmnicasaCRE\Exception;
use GuzzleHttp\Client;
use PackageVersions\Versions;
use InvalidArgumentException;

class ApiAdapter
{
    /** @var Client */
    protected $httpClient;

    private const BASE_URI = 'https://omnicasaapiv3.omnicasa.com/cre/';

    public function __construct($http_client_or_options = null)
    {
        if ($http_client_or_options instanceof Client) {
            $this->httpClient = $http_client_or_options;
        } else if (is_array($http_client_or_options)) {
            $this->httpClient = $this->createHttpClient($http_client_or_options);
        } else if (!is_null($http_client_or_options)) {
            throw new InvalidArgumentException(
                'Argument needs to be an instance of GuzzleHttp\Client or an associative array of options'
            );
        }
    }

    /**
     * Send a request to the API and return the parsed response.
     *
     * @param Request $request
     *
     * @throws Exception\ApiException if a server-side error occurred
     *
     * @return ResponseObject
     */
    public function request(Request $request): ResponseObject
    {
        $response = $this->getHttpClient()->send($request);
        $body = $response->getBody()->getContents();
        $json = json_decode($body, false);

        if (empty($json->Success)) {
            throw new Exception\ApiException($json->Message ?? 'An unknown error occurred', $json->Code ?? 0);
        }

        return new ResponseObject($json);
    }

    public function getHttpClient(): Client
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = $this->createHttpClient();
        }
        return $this->httpClient;
    }

    public function setHttpClient(Client $http_client): self
    {
        $this->httpClient = $http_client;
        return $this;
    }

    protected function createHttpClient(array $options = []): Client
    {
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }
        if (!isset($options['headers']['User-Agent'])) {
            $version = Versions::getVersion('fw4/omnicasa-cre');
            $options['headers']['User-Agent'] = 'fw4-omnicasa-cre/' . $version;
        }

        return new Client(array_merge([
            'timeout' => 10.0,
            'http_errors' => false,
            'base_uri' => self::BASE_URI,
        ], $options));
    }
}
