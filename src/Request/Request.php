<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Request;

use DateTime;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\Uri;

class Request extends GuzzleRequest
{
    /** @var array */
    protected $parameters;

    public function __construct(
        string $method,
        string $endpoint,
        ?array $parameters = null,
        ?array $body = null,
        array $headers = []
    ) {
        $this->parameters = $parameters;

        if (!is_null($body)) {
            $body = \GuzzleHttp\json_encode($this->encode($body));
            $headers['Content-Type'] = 'application/json';
        }

        $headers['Accept'] = 'application/json';

        parent::__construct($method, new Uri($endpoint), $headers, $body);
    }

    public function getUri()
    {
        $uri = parent::getUri();
        if (!is_null($this->parameters)) {
            $parameters = $this->encode($this->parameters);
            foreach ($parameters as $key => $value) {
                $parameters[$key] = $this->implodeParameter($value);
            }
            $uri = Uri::withQueryValues($uri, $parameters);
        }
        return $uri;
    }

    /**
     * Recursively encode a value into a format understood by the API.
     *
     * @param mixed $encodable
     */
    protected function encode($encodable)
    {
        if (is_array($encodable)) {
            foreach ($encodable as $key => $value) {
                $encodable[$key] = $this->encode($value);
            }
        } elseif ($encodable instanceof DateTime) {
            $encodable = $encodable->format('c');
        }
        return $encodable;
    }

    /**
     * Recursively implode array values
     *
     * @param mixed $encodable
     */
    protected function implodeParameter($parameter)
    {
        if (is_array($parameter)) {
            foreach ($parameter as $key => $value) {
                $parameter[$key] = $this->implodeParameter($value);
            }
            return implode(',', $parameter);
        } else {
            return $parameter;
        }
    }

    public function setParameter(string $parameter, $value): self
    {
        $this->parameters[$parameter] = $value;
        return $this;
    }

    public function setParameters(array $parameters): self
    {
        foreach ($parameters as $parameter => $value) {
            $this->parameters[$parameter] = $value;
        }
        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }
}
