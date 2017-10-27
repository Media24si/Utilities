# Media24 Laravel Utilities

A collection of utilities we use in almost all projects.

[![Build Status](https://travis-ci.org/Media24si/Utilities.svg?branch=master)](https://travis-ci.org/Media24si/Utilities)

# Installation

```
composer require media24si/utilities
```

# List of utilities

1. Utilities
    1. [Api Paginator](#apipaginator)
    2. [Sorter](#sorter)
2. Scopes
    1. [WhereWhen](#wherewhen)
    1. [ApiPaginate](#apipaginate)
3. Rules
    1. [CsvIn](#csvin)

# Utilities

## ApiPaginator
An extension of the Laravel LengthAwarePaginator with a custom transformed response.

Example usage:
```php
$model->where('foo', 'bar')->orderBy('foo', 'desc');
$results = ApiPaginator::create($model, 15);
```

An example of the response (with `toArray()` called on the returned paginator object):
```php
[
    'data' => ['item3', 'item4'],
    'pagination' => [
        'total' => 6,
        'per_page' => 2,
        'current_page' => 2,
        'last_page' => 3,
        'next_page_url' => '/?page=3',
        'prev_page_url' => '/?page=1',
        'from' => 3,
        'to' => 4
    ]
]
```

## Sorter
A Rule for validation and trait with a scope. Primarily used to enable easy sorting in API endpoints.

Example usage:

Sorting is determined through the use of the ‘sort’ (for example) query string parameter.
The value of this parameter is a comma-separated list of sort keys. The default sort direction is asc, but can optionally be changed to desc by prefixing any key with "-".

* /api/posts?sort=views
* /api/posts?sort=-views
* /api/posts?sort=comments,-views

```php
// Model
class Post extends Model {
    use \Media24si\Utilities\Scopes\Sorter;
}


// Controller action
public function index(Request $request) {
    $request->validate(['sort' => new \Media24si\Utilities\Rules\Sorter(['views', 'comments'])]);

    $posts = \App\Post::sorter($request->input('sort', ''))->get();
}
```

## Scopes
### WhereWhen
WhereWhen scope trait is an extension to the when method

```php
$model->whereWhen('foo');
// same as
$model->when(request('foo'), function($query) {
  return $query->where('foo', request('foo'));
});

$model->whereWhen('foo', 'bar');
// same as
$model->when(request('bar'), function($query) {
  return $query->where('foo', request('bar'));
});

$model->whereWhen('foo', 'bar', '<');
// same as
$model->when(request('bar'), function($query) {
  return $query->where('foo', '<', request('bar'));
});

$model->whereWhen('foo', 'bar', null, new Request(['bar' => 'foo']));
// same as
$model->when($request->input('bar'), function($query) {
  return $query->where('foo', $request->input('bar'));
});
```

### ApiPaginate
The apiPaginate scope trait can be used to call the ApiPaginator on a builder instance

```php
$model->where('foo', 'bar')->orderBy('foo', 'desc')->apiPaginate(15);
```

##### Same as previously

```php
$model->where('foo', 'bar')->orderBy('foo', 'desc');
$model = ApiPaginator::create($model, 15);
```

##### Trait usage
To use this scope in your model, simply include it as you would any other trait.
```php
class MyModel extends \Illuminate\Database\Eloquent\Model
{
    use \Media24si\Utilities\Scopes\ApiPaginate;
}
```

## Rules
### CsvIn
A rule for validating if a sent csv value string exists within a given set of values.
All sent csv values have to exist inside the provided set of values.

##### Valid

* /api/orders?source=android,ios

```php
$request->validate([
  'source' => new Media24si\Utilities\Rules\CsvIn(['web', 'android', 'ios', 'winphone'])
]);
```

##### Invalid

* /api/orders?source=android,ios,otherval

```php
$request->validate([
  'source' => new Media24si\Utilities\Rules\CsvIn(['web', 'android', 'ios', 'winphone'])
]);
```
