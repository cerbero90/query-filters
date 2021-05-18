# Query Filters

[![Author][ico-author]][link-author]
[![PHP Version][ico-php]][link-php]
[![Laravel Version][ico-laravel]][link-laravel]
[![Octane Compatibility][ico-octane]][link-octane]
[![Build Status][ico-actions]][link-actions]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![PSR-12][ico-psr12]][link-psr12]
[![Total Downloads][ico-downloads]][link-downloads]

[![SensioLabsInsight][ico-sensiolabs]][link-sensiolabs]

Query Filters was fully inspired by [this lesson on Laracasts](https://laracasts.com/series/eloquent-techniques/episodes/4), it provides a simple way to filter Eloquent models based on query parameters of an HTTP request.

## Install

Via Composer:

``` bash
composer require cerbero/query-filters
```

If your version of Laravel is prior to 5.5, you can register this package service provider by adding the following line to the list of providers in `config/app.php`:

``` php
'providers' => [
    ...
    Cerbero\QueryFilters\Providers\QueryFiltersServiceProvider::class,
]
```

This package includes a generator for query filter classes that by default are generated in `app/QueryFilters`. If you prefer a different path, you can set it in the `config/query_filters.php` file after running:

``` bash
php artisan vendor:publish --tag=query_filters_config
```

## Usage

Imagine to have a route for indexing all actors stored in the database. This route accepts query parameters to filter records, for example:

```
/actors?won_oscar&acting=0&acted-in=2000
```

In this case the route will need to display only actors who won at least one Oscar, are no longer acting but acted in 2000.

This can be achieved by generating a query filters class and optionally defining the allowed query parameters and related variable names via the following Artisan command:

``` bash
php artisan make:query-filters ActorFilters 'won_oscar&acting=bool&acted-in=year'
```

That command will generate and populate with filters the `ActorFilters` class:

``` php
use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class ActorFilters extends QueryFilters
{
    /**
     * Filter records based on the query parameter "won_oscar"
     * 
     * @return void
     */
    public function wonOscar()
    {
        // $this->query
    }

    /**
     * Filter records based on the query parameter "acting"
     * 
     * @param mixed $bool
     * @return void
     */
    public function acting($bool)
    {
        // $this->query
    }

    /**
     * Filter records based on the query parameter "acted-in"
     * 
     * @param mixed $year
     * @return void
     */
    public function actedIn($year)
    {
        // $this->query
    }
}
```

Please note how filter names are the equivalent camel case form of their related query parameters.

Filters are only applied if their query parameter is present in the HTTP request with a non-empty value, unless they need no value to function (e.g. `won_oscar`).

The `$query` property lets filters determine how queries change when they are applied:

``` php
public function wonOscar()
{
    $this->query->where('oscars', '>', 0);
}

public function acting($bool)
{
    $this->query->where('acting', $bool);
}

public function actedIn($year)
{
    $this->query->whereHas('movies', function ($movies) use ($year) {
        $movies->whereYear('release_date', '=', $year);
    });
}
```

After filters are defined, Eloquent models can apply them by using the `FiltersRecords` trait:

``` php
use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use FiltersRecords;
}
```

Finally in your route you can filter actors by calling the method `filterBy()` of your model and passing the query filters:

``` php
use App\Actor;
use App\QueryFilters\ActorFilters;

...

public function index(ActorFilters $filters)
{
    return Actor::filterBy($filters)->get();
}
```

Alternatively you can hydrate query filters from a plain array:

``` php
use App\Actor;
use App\QueryFilters\ActorFilters;
use Illuminate\Http\Request;

...

public function index(Request $request)
{
    $filters = ActorFilters::hydrate($request->query());

    return Actor::filterBy($filters)->get();
}
```

Sometimes you may want to silently skip filters if their value is not valid. Instead of setting validation rules in HTTP requests, you may define them in the query filters class.

This avoids a failed validation to stop the filtering process and applies only valid filters while ignoring filters with values that do not pass the validation:

```php
class ActorFilters extends QueryFilters
{
    protected function getRules()
    {
        return [
            'acting' => 'bool',
            'acted-in' => 'int|digits:4',
        ];
    }
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email andrea.marco.sartori@gmail.com instead of
using the issue tracker.

## Credits

- [Jeffrey Way](https://github.com/JeffreyWay)
- [Laracasts](https://laracasts.com)
- [Andrea Marco Sartori][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-author]: https://img.shields.io/static/v1?label=author&message=cerbero90&color=50ABF1&logo=twitter&style=flat-square
[ico-php]: https://img.shields.io/packagist/php-v/cerbero/query-filters?color=%234F5B93&logo=php&style=flat-square
[ico-laravel]: https://img.shields.io/static/v1?label=laravel&message=%E2%89%A55.4&color=ff2d20&logo=laravel&style=flat-square
[ico-octane]: https://img.shields.io/static/v1?label=octane&message=compatible&color=ff2d20&logo=laravel&style=flat-square
[ico-version]: https://img.shields.io/packagist/v/cerbero/query-filters.svg?label=version&style=flat-square
[ico-actions]: https://img.shields.io/github/workflow/status/cerbero90/query-filters/build?style=flat-square&logo=github
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-psr12]: https://img.shields.io/static/v1?label=compliance&message=PSR-12&color=blue&style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cerbero90/query-filters.svg?style=flat-square&logo=scrutinizer
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cerbero90/query-filters.svg?style=flat-square&logo=scrutinizer
[ico-downloads]: https://img.shields.io/packagist/dt/cerbero/query-filters.svg?style=flat-square
[ico-sensiolabs]: https://insight.sensiolabs.com/projects/fe5cb80b-d49f-46e6-b94b-79c6087b5c13/big.png

[link-author]: https://twitter.com/cerbero90
[link-php]: https://www.php.net
[link-laravel]: https://laravel.com
[link-octane]: https://github.com/laravel/octane
[link-packagist]: https://packagist.org/packages/cerbero/query-filters
[link-actions]: https://github.com/cerbero90/query-filters/actions?query=workflow%3Abuild
[link-psr12]: https://www.php-fig.org/psr/psr-12/
[link-scrutinizer]: https://scrutinizer-ci.com/g/cerbero90/query-filters/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cerbero90/query-filters
[link-downloads]: https://packagist.org/packages/cerbero/query-filters
[link-sensiolabs]: https://insight.sensiolabs.com/projects/fe5cb80b-d49f-46e6-b94b-79c6087b5c13
[link-contributors]: ../../contributors
