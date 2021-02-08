# Omnicasa CRE API

PHP client for the [Omnicasa](https://www.omnicasa.com) API for commercial real estate. For terms of use and API
credentials, contact Omnicasa support and refer to the official documentation.

## Installation

`composer require fw4/omnicasa-cre`

## Usage

```php
use OmnicasaCRE\Omnicasa;
use OmnicasaCRE\Enums\Language;

$api = new Omnicasa('your-secret-key', Language::EN);
$properties = $api->getProperties();
```

All endpoints are provided as methods of the Omnicasa class. For more information about available endpoints and
response format, refer to the official API documentation.

### Available endpoints

Use the following methods to access available endpoints:

```php
$api->getProperties($parameters); // Get basic property list
$api->getProperties($parameters, true); // Get detailed property list
$api->getProperty($id, $parameters);
$api->addPropertyVisit($id, $real_client_ip);
$api->getGoals($parameters);
$api->getPropertyTypes($parameters);
$api->getCities($parameters);
$api->registerPerson($parameters);
$api->contactOnMe($parameters);
```

### Pagination

Endpoints that retrieve multiple items return a traversable list of objects. Pagination for large lists happens
automatically.

```php
$properties = $api->getProperties();

// Traversing over the response takes care of pagination in the background
foreach ($properties as $property) {
    echo $property->id . PHP_EOL;
}
```

### Manual pagination

For situations where manual pagination is required, a `page` method is provided. Calling this method with both a
desired page index (starting at 0), and the amount of items to retrieve per page, returns a traversable list of
objects. This list also provides multiple methods for dealing with paging metadata:

- `getPage()` to retrieve the current page index (starting at 0).
- `getPageSize()` to retrieve the maximum amount of items per page.
- `count()` to retrieve the actual amount of items on the current page.
- `getTotalCount()` to retrieve the total amount of items across all pages. This method is currently not available on
`activities` endpoints.
- `getPageCount()` to retrieve the total amount of pages. This method is currently not available on
`activities` endpoints.

#### Example

```php
$page_index = 2;
$items_per_page = 20;

$properties = $api->getProperties();
$page = $properties->page($page_index, $items_per_page);

echo 'Showing ' . $page->count() . ' items out of ' . $page->getTotalCount() . PHP_EOL;
echo 'Page ' . ($page->getPage() + 1) . ' of ' . $page->getPageCount() . PHP_EOL;
foreach ($page as $property) {
    echo $property->id . PHP_EOL;
}
```

## License

`fw4/omnicasa-cre` is licensed under the MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
