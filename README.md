Burroughs Test [![Build Status](https://travis-ci.org/kaurov/inviqa-burroughs-test.svg?branch=master)](https://travis-ci.org/kaurov/inviqa-burroughs-test)
=====================

## About

This application calculates the payout dates of given processes (regular salary and additional bonus payout).
It can easily be configured differently by changing the config data in the parameter.yml.
Alternatively additional processes can be configured and implemented to extend the application to your needs.
Take look in app/config/parameters.yml for additional information about how to configure a process.

## Installation

This application has dependencies you will need to install prior usage. But don't worry. Composer will take care
of this. Just follow the description...

### Clone project

First clone the project, if not already done so.

```
git clone https://github.com/kaurov/inviqa-burroughs-test.git
```

### Download Composer

Within the project folder, download the Composer

```
curl -sS https://getcomposer.org/installer | php
```

### Install Composer

Install dependencies

```
php composer.phar install
```

Later on in order to update dependencies after updating this app just do

```
php composer.phar update
```

## Usage

To start the application call
```
php index.php payout:generate {filename}
```

with filename as your desired filename for the report that is being generated.

Example:
```
php index.php payout:generate report.csv
```

## Run Tests

This Application has test cases for its processors. To run them do the following:

```
php vendor/bin/phpunit --colors --coverage-text --configuration tests/phpunit.xml.dist  tests/
```


## RESULTS: 
see ./report.csv
month	salary	bonus
2018-01	2018-01-31	2018-01-15
2018-02	2018-02-28	2018-02-15
2018-03	2018-03-30	2018-03-15
2018-04	2018-04-30	2018-04-18
2018-05	2018-05-31	2018-05-15
2018-06	2018-06-29	2018-06-15
2018-07	2018-07-31	2018-07-18
2018-08	2018-08-31	2018-08-15
2018-09	2018-09-28	2018-09-19
2018-10	2018-10-31	2018-10-15
2018-11	2018-11-30	2018-11-15
2018-12	2018-12-31	2018-12-19
