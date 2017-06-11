# ShiftPHP - PHP Wrapper for SHIFT API

ShiftPHP is a set of PHP classes to call the SHIFT (https://www.shiftnrg.org) API from a PHP project.

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

## Errors

Every API command that returns the fields "success" and "error" can throw a CommandException that you can use to adapt your code.
```php
try {
    $voters = $client->getVoters($pubKey);
} catch (CommandException $e){
    echo "Error ! Msg : " . $e->getMessage()."\n";
}
```

## Test

I did not have time to test every API commands, feel free to open issues / pull request :)

## Bonus

If you liked it, you can upvote my delegate "yondz" and/or donate to my SHIFT wallet : 17047213952582854284S