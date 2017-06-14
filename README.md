# ShiftPHP - PHP Wrapper for SHIFT API

ShiftPHP is a set of PHP classes to call the SHIFT (https://www.shiftnrg.org) API from a PHP project.
## Prerequisites

You will need the curl PHP package installed (php7.1-curl for php7.1, php7.0-curl for php7.0 and php5-curl for php < 7)

## Usage

### Clone this repository
### Include the autoloader

```php
// Setup
require_once "Autoload.php";
use ShiftPHP\Classes\ShiftAPI;
```
### Use the ShiftAPI by creating a client object with the host of your API

```php
$host = "http://localhost";
$client = new ShiftAPI($host);
echo "Total supply : " . $client->getSupply();
```

Note: Every optionnal parameter can be set to null, and will not be sent to the request.

## Errors

Every API command that returns the fields "success" and "error" can throw a CommandException that you can use to adapt your code.
```php
try {
    $voters = $client->getVoters($pubKey);
} catch (CommandException $e){
    echo "Error ! Msg : " . $e->getMessage()."\n";
}
```

## Caching

To avoid overloading your API endpoints, I've added a simple text cache for every GET command. It will produce a file inside a cache directory containing the response from the server, with a default lifetime of 300s (5 mins), this can be adjusted when instanciating the clients

To enable the Cache system, you will have to use methods enableCache() / disableCache()
```php
$host = "http://localhost";
$client = new ShiftAPI($host);
$client->enableCache(); // Cache enabled with default lifetime (300s)
$client->setLifetime(60); // Cache lifetime changed to 60s
$client->setCacheFolder("cache/shift"); //Changed cache folder for this client
echo "Total supply : " . $client->getSupply();
```

Note: There is no cache clear at the moment, only obsolete file are regenerated if necessary.

## Test

I did not have time to test every API commands, feel free to open issues / pull request :)

## Bonus

If you liked it, you can upvote my delegate "yondz" and/or donate to my SHIFT wallet : 17047213952582854284S
