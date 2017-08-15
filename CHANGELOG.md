# Changelog

All Notable changes to `query-filters` will be documented in this file.

Updates should follow the [Keep a CHANGELOG](http://keepachangelog.com/) principles.

## 1.0.0 - 2016-04-26

### Added
- Abstract class to extend for implementing query filters.
- Trait to let Eloquent models use in order to filter their records.


## 1.1.0 - 2016-07-25

### Added
- Static method to hydrate the filters from plain array.


## 1.2.0 - 2016-11-19

### Added
- Artisan command to generate query filters.
- Configuration file to set the path of generated query filters.


## 1.2.1 - 2016-11-19

### Removed
- Support for PHP 5.5.


## 1.3.0 - 2017-08-15

### Added
- Skip implicit filters by default.
- Register implicit filters not to be skipped.
