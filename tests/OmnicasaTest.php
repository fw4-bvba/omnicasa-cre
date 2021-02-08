<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Tests;

use OmnicasaCRE\Omnicasa;
use OmnicasaCRE\Enums\Language;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class OmnicasaTest extends ApiTestCase
{
    public function testConstructor(): void
    {
        $api = new Omnicasa('test');

        $client = $api->getHttpClient();
        $this->assertInstanceOf(Client::class, $client);

        $api->setHttpClient($client);
        $client = $api->getHttpClient();
        $this->assertInstanceOf(Client::class, $client);
    }

    public function testGetProperties(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Items":[{"Id":2088}],"RowsCount":1,"From":1,"To":10}}');

        $properties = self::$api->getProperties();

        $this->assertEquals(1, count($properties));
        $this->assertEquals(2088, $properties[0]->id);
    }

    public function testGetProperty(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Id":2088}}');

        $property = self::$api->getProperty(2088);

        $this->assertEquals(2088, $property->id);
    }

    public function testAddPropertyVisit(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Id":2088}}');

        $response = self::$api->addPropertyVisit(2088, 'test', 1);

        $this->assertEquals(2088, $response->id);
    }

    public function testGetGoals(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Items":[{"Id":123}],"RowsCount":1,"From":1,"To":10}}');

        $goals = self::$api->getGoals();

        $this->assertEquals(1, count($goals));
        $this->assertEquals(123, $goals[0]->id);
    }

    public function testGetPropertyTypes(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Items":[{"Id":123}],"RowsCount":1,"From":1,"To":10}}');

        $types = self::$api->getPropertyTypes();

        $this->assertEquals(1, count($types));
        $this->assertEquals(123, $types[0]->id);
    }

    public function testGetCities(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":{"Items":[{"Id":123}],"RowsCount":1,"From":1,"To":10}}');

        $cities = self::$api->getCities();

        $this->assertEquals(1, count($cities));
        $this->assertEquals(123, $cities[0]->id);
    }

    public function testRegisterPerson(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":123}');

        $id = self::$api->registerPerson([]);

        $this->assertEquals(123, $id);
    }

    public function testContactOnMe(): void
    {
        $this->queueResponse('{"Success":true,"Message":"","Value":123}');

        $id = self::$api->contactOnMe([]);

        $this->assertEquals(123, $id);
    }

    public function testSetPageSize(): void
    {
        Omnicasa::setDefaultPageSize(24);
        $this->assertEquals(24, Omnicasa::getDefaultPageSize());
    }

    public function testDefaultLanguage(): void
    {
        $api = new Omnicasa('', Language::EN, [
            'handler' => new HandlerStack(self::$mockHandler)
        ]);
        $this->assertEquals(Language::EN, $api->getDefaultLanguage());

        $api->setDefaultLanguage(Language::FR);
        $this->assertEquals(Language::FR, $api->getDefaultLanguage());

        $this->queueResponse('{"Success":true,"Message":"","Value":{"Id":1}}');
        $property = $api->getProperty(1);
        $this->assertEquals('https://omnicasaapiv3.omnicasa.com/cre/property//1?LanguageId=2', strval(self::$mockHandler->getLastRequest()->getUri()));
    }
}
