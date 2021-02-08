<?php

/*
 * This file is part of the fw4/omnicasa-cre library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OmnicasaCRE\Response;

use OmnicasaCRE\Exception\InvalidDataException;

class Response extends ResponseObject
{
    public function __construct(ResponseObject $response)
    {
        if (empty($response->Value)) {
            throw new InvalidDataException('Response is missing data.');
        }
        $this->setData($response->Value->getData());
    }
}
