# DynamicEndpoints
A super-simple PHP library for creating nice APIs. It allows you to have urls like api.foo.com/my/endpoint/2

## The Idea
When writing an HTTP API in PHP it is much nicer to have a url like this: `api.foo.com/books/2` instead of this `api.foo.com/books.php?id=2`. This is simple enough to do, use a custom apache 404 page, then parse the URL, but that can be a pain. So I made a tiny library for it.

## Usage
For a working example, see the test directory.
###.htaccess
In the root folder you have to have a `.htaccess` file that defines a main page, perhaps `index.php` as the 404 ErrorDoucument. It could look like this:
```
ErrorDocument 404 /dynamic-endpoints/test/index.php
```
### PHP
First, import the sources:
```php
require_once(__DIR__ . "/../src/DynamicEndpoint.php");
```
Next, create an API object. `$base` is the path that you want all the APIs to come off of. For example for something like this: `http://foo.com/my-thing/api/` the `$base` would be `/my-thing/api`. Base can be blank, for something at the root url.
```php
$base = "/dynamic-endpoints/test";
$API = new API($base);
```
Now, you have to register the endpoints. These define what URLs can be accessed through the API. They can contain named variables and wildcards:
```php
//Register the endpoints
//Variables are defined with %varName
//A .. allows anyhting
$API->register(Array(
    "/api/movie/../%name/%prop/.." => __DIR__ . "/movie.php",
    "/api/movie/" => __DIR__ . "/movie2.php",
    "/api/book/%id" => __DIR__ . "/book.php",
    "/api/book/name/%name" => __DIR__ . "/book2.php"
));
```
So `/api/book/%id` will match this `/api/book/2` and it will pass `$id` with value 2.

Now we simply run the API. This will include the file specified if the enpoint matches. The variables will be set as specified in the scope of the file. If no endpoint is found, and array will be returned with an key of `"Error"`.
```php
//Look for an endpoint, will run the file if one is found
$result = $API->runEndpoint();
if (array_key_exists("error", $result)) var_dump ($result);
```