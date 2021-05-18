# Changelog

All Notable changes to `query-filters` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.


## 1.8.0 - 2021-05-18

### Changed
- Minor adjustments to support Laravel Octane.


## 1.7.0 - 2021-01-14

### Added
- Support for PHP 8.

### Changed
- Migrated from Travis to GitHub actions.
- Code style from PSR-2 to PSR-12.


## 1.6.0 - 2020-10-15

### Added
- Support for Laravel 8.


## 1.5.0 - 2020-07-14

### Added
- Values of filters can be validated: only filters that pass the validation are applied.


## 1.4.1 - 2020-03-04

### Added
- Support for Laravel 7.


## 1.4.0 - 2019-09-22

### Added
- The Artisan console command can generate classes populated with filters.
- Automatic determination of implicit filters
- Support for Laravel 6.
- Method to retrieve the request that query filters are based on.

### Removed
- Need to list implicit filters.


## 1.3.1 - 2017-08-16

### Fixed
- Filters are considered implicit also if they have null values.


## 1.3.0 - 2017-08-15

### Added
- Skip implicit filters by default.
- Register implicit filters not to be skipped.


## 1.2.1 - 2016-11-19

### Removed
- Support for PHP 5.5.


## 1.2.0 - 2016-11-19

### Added
- Artisan command to generate query filters.
- Configuration file to set the path of generated query filters.

## 1.1.0 - 2016-07-25

### Added
- Static method to hydrate the filters from plain array.


## 1.0.0 - 2016-04-26

### Added
- Abstract class to extend for implementing query filters.
- Trait to let Eloquent models use in order to filter their records.
