<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE;

use OmnicasaCRE\Response\Response;
use OmnicasaCRE\Response\Collection;
use OmnicasaCRE\Request\Request;
use OmnicasaCRE\Request\CollectionRequest;
use GuzzleHttp\Client;

final class Omnicasa
{
    /** @var string */
    private $secret;

    /** @var ApiAdapter */
    private $apiAdapter;

    /** @var int */
    private $defaultLanguage;

    /** @var int */
    protected static $defaultPageSize = 50;

    public function __construct(string $secret, ?int $default_language = null, $http_client_or_options = null)
    {
        $this->secret = $secret;
        $this->setDefaultLanguage($default_language);
        $this->apiAdapter = new ApiAdapter($http_client_or_options);
    }

    // Endpoints

    /**
     * Get a list of properties.
     *
     * @param array $parameters Associative array of request parameters
     * @param bool $includeDetails Pass true to request full data for properties
     *
     * @return Collection
     */
    public function getProperties(array $parameters = [], bool $includeDetails = false): Collection
    {
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new CollectionRequest('GET', (
            $includeDetails ? 'property/full/' : 'property/'
        ) . $this->secret, $parameters);
        return new Collection($request, $this->apiAdapter);
    }

    /**
     * Get a single property by ID.
     *
     * @param int $id ID of the property to request
     * @param array $parameters Associative array of request parameters
     *
     * @return Response
     */
    public function getProperty(int $id, array $parameters = []): Response
    {
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new Request('GET', 'property/' . $this->secret . '/' . $id, $parameters);
        return new Response($this->apiAdapter->request($request));
    }

    /**
     * @param int $id
     * @param string $real_client_ip
     * @param int|null $language
     *
     * @return Response
     */
    public function addPropertyVisit(int $id, string $real_client_ip, ?int $language = null): Response
    {
        $parameters = [
            'PropertyId' => $id,
            'RealClientIP' => $real_client_ip,
        ];
        if (!is_null($language)) {
            $parameters['LanguageId'] = $language;
        }
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new Request('GET', 'property/addvisit/' . $this->secret, $parameters);
        return new Response($this->apiAdapter->request($request));
    }

    /**
     * Get a list of available goals.
     *
     * @param array $parameters Associative array of request parameters
     *
     * @return Collection
     */
    public function getGoals(array $parameters = []): Collection
    {
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new CollectionRequest('GET', 'goal/' . $this->secret, $parameters);
        return new Collection($request, $this->apiAdapter);
    }

    /**
     * Get a list of property types.
     *
     * @param array $parameters Associative array of request parameters
     *
     * @return Collection
     */
    public function getPropertyTypes(array $parameters = []): Collection
    {
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new CollectionRequest('GET', 'propertytype/' . $this->secret, $parameters);
        return new Collection($request, $this->apiAdapter);
    }

    /**
     * Get a list of cities.
     *
     * @param array $parameters Associative array of request parameters
     *
     * @return Collection
     */
    public function getCities(array $parameters = []): Collection
    {
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new CollectionRequest('GET', 'city/' . $this->secret, $parameters);
        return new Collection($request, $this->apiAdapter);
    }

    /**
     * Create or update a person.
     *
     * @param array $parameters Associative array of request parameters
     *
     * @return int ID of the created contact
     */
    public function registerPerson(array $parameters = []): int
    {
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new Request('POST', 'person/register/' . $this->secret, null, $parameters);
        return $this->apiAdapter->request($request)->value;
    }

    /**
     * Create or update a person and include a message.
     *
     * @param array $parameters Associative array of request parameters
     *
     * @return int ID of the created contact
     */
    public function contactOnMe(array $parameters = []): int
    {
        $parameters = $this->applyDefaultLanguage($parameters);
        $request = new Request('POST', 'contactonme/' . $this->secret, null, $parameters);
        return $this->apiAdapter->request($request)->value;
    }

    // Default language

    /**
     * Set the language to use for requests without a LanguageId parameter.
     *
     * @param int|null $language
     *
     * @return self
     */
    public function setDefaultLanguage(?int $language): self
    {
        $this->defaultLanguage = $language;
        return $this;
    }

    /**
     * Get the language being used for requests without a LanguageId parameter.
     *
     * @return int|null
     */
    public function getDefaultLanguage(): ?int
    {
        return $this->defaultLanguage;
    }

    /**
     * Apply the default language to a set of parameters if they're missing a
     * LanguageId parameter.
     *
     * @param array $parameters
     *
     * @return array
     */
    protected function applyDefaultLanguage(array $parameters): array
    {
        if (empty($parameters['LanguageId']) && !is_null($this->defaultLanguage)) {
            $parameters['LanguageId'] = $this->defaultLanguage;
        }
        return $parameters;
    }

    // HTTP Client

    public function setHttpClient(Client $client): self
    {
        $this->apiAdapter->setHttpClient($client);
        return $this;
    }

    public function getHttpClient(): Client
    {
        return $this->apiAdapter->getHttpClient();
    }

    /**
     * Set the page size to be used by automatic pagination.
     *
     * @param int $page_size
     */
    public static function setDefaultPageSize(int $page_size): void
    {
        self::$defaultPageSize = $page_size;
    }

    /**
     * Get the page size that is used by automatic pagination.
     *
     * @return int
     */
    public static function getDefaultPageSize(): int
    {
        return self::$defaultPageSize;
    }
}
