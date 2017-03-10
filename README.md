# Imagelint PHP

[![Build Status](https://travis-ci.org/imagelint/imagelint-php.svg?branch=master)](https://travis-ci.org/imagelint/imagelint-php)
[![codecov](https://codecov.io/gh/imagelint/imagelint-php/branch/master/graph/badge.svg)](https://codecov.io/gh/imagelint/imagelint-php)

A class to convert your image URLs to Imagelint URLs

## Installation

You can add this library to your project using [Composer](https://getcomposer.org/):

    composer require imagelint/imagelint-php

## Usage

### Basic usage

```php
Imagelint\Imagelint::get('http://yoursite.com/img/cat.jpg')
```

The code above yields the output below:

    https://a1.imagelint.com/yoursite.com/img/cat.jpg

### You can also use parameters

```php
Imagelint\Imagelint::get('http://yoursite.com/img/cat.jpg', ['width' => 200])
```

The code above scales the image to a width of 200px.