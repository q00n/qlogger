# qlogger

Simple logger

Providing data logging in file.

## Install

Via Composer

``` bash
$ composer require q00n/qlogger
```

Thats all! Package Auto-Discovery is coming to Laravel 5.5 and you'll no longer have to manually define the Service Provider or Alias in the config app file. 

## Config

To change default logs path ("storage/logger") or logs expire date (7 days), just publish package config and set needed properties.

``` bash
php artisan vendor:publish --tag="qlogger-config"
```

## Usage

``` php
$data = $request->name;

QLogger::save($data, 'Input Data');
```

To read/erase/remove logs visit /qlogger page. There you will have a pleasent, intuitive interface.

## Avaible methods

``` php
QLogger::save($data[, $mark]); // save passed data with 'NOTICE' level of importance
QLogger::info($data[, $mark]); // save passed data with 'INFO' level of importance
QLogger::danger($data[, $mark]); // save passed data with 'DANGER' level of importance
QLogger::success($data[, $mark]); // save passed data with 'SUCCESS' level of importance

QLogger::request($key); // save data from Request::all($key)
QLogger::input($key); // save data from Request::input($key)
QLogger::json($key); // save data from Request::json($key)
QLogger::post($key); // save data from $_POST[$key]
QLogger::get($key); // save data from $_GET[$key]
QLogger::php(); // save data from Request::getContent()
QLogger::server($key); // save data from $_SERVER[$key]
QLogger::cookies($key); // save data from Request::cookie($key)
QLogger::headers($key); // save data from Request::header($key)
```

## Parameters
```
data - required parameter (string, number, boolean value, array, object)

mark - text mark for filtering logs, optional parameter (default: null)

key - specifies a particular key from an array or object to be written, optional parameter (default: null)
```
