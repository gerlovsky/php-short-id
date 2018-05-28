# PHP Short ID creator

[![Build Status](https://travis-ci.org/gerlovsky/php-short-id.svg?branch=master)](https://travis-ci.org/gerlovsky/php-short-id)

The library help you generate short id like youtube, vimeo, bit.ly, etc. Short generation (creation) based on numerical ID. 

## Simple scenarios of using

```php
require('vendor/autoload.php');

$shortId = new \Gerlovsky\ShortId\ShortId();
```
 
### Creating short ID for a record from in a database

1. when an app created a record in an your database with ID 20956
2. $shortId->encode(20956) encodes it to 'bfrE'
3. you updated the record for ID 20956 and set short_id of the record to 'bfrE'

```php
$id = $shortId->encode(20956);     // $id will be 'bfrE'

// or with $length = 6
$id = $shortId->encode(20956, 6);  // $id will be 'baauC6'
```
