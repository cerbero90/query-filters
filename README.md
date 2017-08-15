# Query Filters

[![Author][ico-author]][link-author]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![StyleCI][ico-styleci]][link-styleci]
[![Total Downloads][ico-downloads]][link-downloads]
[![Gratipay][ico-gratipay]][link-gratipay]

[![SensioLabsInsight][ico-sensiolabs]][link-sensiolabs]

Query Filters has been fully inspired by this
[lesson on Laracasts](https://laracasts.com/series/eloquent-techniques/episodes/4).
This package provides an elegant and dynamic way to filter database records based on the request query string.

## Install

From the root of your project run the following command in the terminal:

``` bash
composer require cerbero/query-filters
```

Optionally you can register the service provider included in the package to create query filters via the
Artisan command `make:query-filters FiltersName`. In order to do that, add the following line to the list
of your providers in `config/app.php`:

``` php
'providers' => [
    ...
    Cerbero\QueryFilters\QueryFiltersServiceProvider::class,
    ...
]
```

By default the newly created query filters are placed in `app/QueryFilters`, if you prefer a different
path you can set it in the `config/query_filters.php` file that you can create by running:

``` bash
php artisan vendor:publish --tag=query_filters_config
```

## Usage

Imagine having a route to index all the actors stored in our database.
This route accepts a query string to filter the data to display. For instance:

```
/actors?won_oscar&acting=0&acted-in=2000
```

will display only actors who won at least one Oscar, are no longer acting but acted in 2000.

By using this package you can easily create filters based on the requested query string by just extending
the `QueryFilters` class:

``` php
use Cerbero\QueryFilters\QueryFilters;

class ActorFilters extends QueryFilters
{
    protected implicitFilters = [
        'wonOscar',
    ];

    public function wonOscar()
    {
        $this->query->where('oscars', '>', 0);
    }

    public function acting($boolean)
    {
        $this->query->whereActing($boolean);
    }

    public function actedIn($year)
    {
        $this->query->whereHas('movies', function ($movies) use ($year) {
            $movies->whereYear('release_date', '=', $year);
        });
    }
}
```

All parameters in the query string have the related method in the newly created class.
Please note that parameters having dashes or underscores are converted into their respective camel case form.

By default filters are not applied whether their value is an empty string.
If you wish to have implicit filters (e.g. `wonOscar()`) you can list them in the property `$implicitFilters`
and they will be applied just like the others when the related query parameter is present in the request.

You can use the property `$query` to interact with the Laravel Query Builder and determine how filters work.
Thereafter let your Eloquent model (e.g. Actor) use the `FiltersRecords` trait:

``` php
use Cerbero\QueryFilters\FiltersRecords;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use FiltersRecords;
}
```

Now you can filter your actors by calling the method `filterBy()` and passing an instance of `ActorFilters`.
For example, in your controller:

``` php
use App\Actor;
use App\QueryFilters\ActorFilters;

...

public function index(ActorFilters $filters)
{
    return Actor::filterBy($filters)->get();
}
```

Alternatively you can hydrate an instance of `QueryFilters` from an array of query parameters, like:

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

[ico-author]: http://img.shields.io/badge/author-@cerbero90-blue.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/cerbero/query-filters.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/cerbero90/query-filters/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/cerbero90/query-filters.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/cerbero90/query-filters.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/57024205/shield
[ico-downloads]: https://img.shields.io/packagist/dt/cerbero/query-filters.svg?style=flat-square
[ico-gratipay]: https://img.shields.io/gratipay/cerbero.svg?style=flat-square
[ico-sensiolabs]: https://insight.sensiolabs.com/projects/fe5cb80b-d49f-46e6-b94b-79c6087b5c13/big.png

[link-author]: https://twitter.com/cerbero90
[link-packagist]: https://packagist.org/packages/cerbero/query-filters
[link-travis]: https://travis-ci.org/cerbero90/query-filters
[link-scrutinizer]: https://scrutinizer-ci.com/g/cerbero90/query-filters/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/cerbero90/query-filters
[link-styleci]: https://styleci.io/repos/57024205
[link-downloads]: https://packagist.org/packages/cerbero/query-filters
[link-gratipay]: https://gratipay.com/cerbero
[link-sensiolabs]: https://insight.sensiolabs.com/projects/fe5cb80b-d49f-46e6-b94b-79c6087b5c13
[link-contributors]: ../../contributors
