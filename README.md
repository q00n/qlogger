# qlogger

Simple logger

## Install

Via Composer

``` bash
$ composer require q00n/qlogger
```

## Usage

``` php
QLogger::save($message, 'Input Data');
```

## Avaible methods

``` php
QLogger::save($message, 'Input Data');
QLogger::info($message, 'Input Data');
QLogger::danger($message, 'Input Data');
QLogger::success($message, 'Input Data');

QLogger::request();
QLogger::input();
QLogger::json();
QLogger::post();
QLogger::get();
QLogger::php();
QLogger::server();
QLogger::cookies();
QLogger::headers();
```