# Bappsy php sdk
This SDK will help you to build your own Integrations for
Business Application System (Bappsy) by hochdreih.
If you are not already a Customer you can get your account [here](https://register.bappsy.com)
The Bappsy API provides you with over 400 Data Models and around 2500 Endpoints.

Bappsy can manage nearly everything that is required to run a modern Enterprise

## Installation
`composer require hochdreih/bappsy`

## Authentication
1. [Get your API Key](https://app.bappsy.com/#/config/api)
2. Set up your App
    + provide it in the constructor
```php
<?php
$bappsy = new Bapsy("<yourAPIKey>");
```


## Examples

### General Usage
```php
<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Autoload files using Composer autoload

use Bappsy\Bappsy;

$bappsy = new Bappsy("<yourAPIKey>");



$get = $bappsy->get($endpoint, $q, $prePart, $postPart);
$getDetail = $bappsy->getDetail($endpoint, $id, $q, $prePart, $postPart);
$copy = $bappsy->copy( $endpoint, $id);
$delete = $bappsy->delete( $type, $id);
$update = $bappsy->update($endpoint, $id, $data, $q, $prePart, $postPart);
$create = $bappsy->create($endpoint, $data, $prePart, $postPart);

```

### Use Endpoints directly
The SDK Provides you with
``
get() getDetail() create() update() ->delete()``
methods for general data manipulation
Find all Endpoints in the [API Documentation](https://api-v1.bappsy.com/api-docs/v3/#/)


#### Credits
Made with ❤️ at Tegernsee and Erding by hochdreih
