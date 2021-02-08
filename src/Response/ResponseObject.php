<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Response;

use OmnicasaCRE\Exception\InvalidPropertyException;
use OmnicasaCRE\Exception\InvalidDataException;

class ResponseObject implements \JsonSerializable
{
    /** @var array */
    protected $_data = [];

    /** @var array */
    private $_propertyIndex = [];

    public function __construct($data)
    {
        $this->setData($data);
    }

    /**
     * Recursively parse data returned by the API.
     *
     * @param mixed $value
     * @param string|null $property Name of the property to parse
     *
     * @return self
     */
    protected function parseValue($value, ?string $property = null)
    {
        if (is_object($value)) {
            return new self($value);
        } elseif (is_array($value)) {
            $result = [];
            foreach ($value as &$subvalue) {
                $result[] = $this->parseValue($subvalue, $property);
            }
            return $result;
        } else if (isset($property) && is_string($value) && (strpos($property, 'Date') === strlen($property) - 4 || strpos($property, 'Date') === 0)) {
            return \DateTime::createFromFormat('Y-m-d H:i:s', $value) ?: $value;
        } else {
            return $value;
        }
    }

    /**
     * Get all properties of this object.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->_data;
    }

    protected function setData($data): void
    {
        if (!is_iterable($data) && !is_object($data)) {
            throw new InvalidDataException('ResponseObject does not accept data of type "' . gettype($data) . '"');
        }
        $this->_data = $this->_propertyIndex = [];
        foreach ($data as $property => &$value) {
            $this->_propertyIndex[strtolower($property)] = $property;
            $this->_data[$property] = $this->parseValue($value, $property);
        }
    }

    public function __get(string $property)
    {
        $property = $this->normalizePropertyName($property);
        return $this->_data[$property] ?? null;
    }

    public function __set(string $property, $value)
    {
        $this->_propertyIndex[strtolower($property)] = $property;
        $this->_data[$property] = $value;
    }

    public function __isset(string $property): bool
    {
        $index = strtolower($property);
        return isset($this->_propertyIndex[$index]);
    }

    public function __unset(string $property)
    {
        $property = $this->normalizePropertyName($property);
        unset($this->_propertyIndex[strtolower($property)]);
        unset($this->_data[$property]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function __debugInfo()
    {
        return $this->getData();
    }

    /**
     * Get the correctly cased property name.
     *
     * @param string $property
     *
     * @throws InvalidPropertyException if the property does not exist
     *
     * @return string
     */
    protected function normalizePropertyName(string $property): string
    {
        $index = strtolower($property);
        if (!isset($this->_propertyIndex[$index])) {
            throw new InvalidPropertyException($property . ' is not a valid property of ' . static::class);
        }
        return $this->_propertyIndex[$index];
    }

    /* JsonSerializable implementation */

    public function jsonSerialize()
    {
        return $this->jsonEncode($this->getData());
    }

    protected function jsonEncode($value)
    {
        if (is_array($value)) {
            return array_map([$this, 'jsonEncode'], $value);
        } else if ($value instanceof \DateTime) {
            return $value->format('Y-m-d H:i:s');
        } else {
            return $value;
        }
    }
}
