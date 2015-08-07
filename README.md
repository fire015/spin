Spin Template
==========

[![Total Downloads](https://img.shields.io/packagist/dm/spin/template.svg)](https://packagist.org/packages/spin/template)
[![Build Status](https://travis-ci.org/fire015/spin.svg?branch=master)](https://travis-ci.org/fire015/spin)

An Active Record like pattern for template rendering with PHP.

* Define and retrieve variables
* Accessor and mutator functions
* Render in any engine
* Built in support for Twig, Smarty, Plates and Dwoo

## Installation

Install Spin via [composer](http://getcomposer.org/). Then extend `Spin\Template\Template`.

```
composer require spin/template
```

## Example

```php
class Homepage extends Spin\Template\Template
{
	/**
	 * The template file
	 */
	protected $file = 'templates/home.php';

	/**
	 * Convert the first character of first name to uppercase on retrieval
	 */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

	/**
	 * Convert the whole string to lowercase when setting first name
	 */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

	/**
	 * Convert the unix timestamp to a human friendly date on retrieval
	 */
	public function getDateAttribute($value)
	{
		return date('Y-m-d', $value);
	}
}

$template = new Homepage();

// Assign variables as properties
$template->first_name = 'john';
$template->date = time();

// You can also assign variables as an array
$template['weather'] = 'frightful';

// Render the template
echo $template->render();
```

templates/home.php:
```php
Hello <?php echo $first_name; ?> today's date is <?php echo $date; ?>. The weather outside is <?php echo $weather; ?>
```

Output:
```
Hello John today's date is 2015-08-05. The weather outside is frightful.
```

As you can see we assign variables to our class either via properties or array syntax and call the `render()` method, which passes those variables to our template defined in `$file` and return's the rendered template.

#### Accessors and mutators

Accessors and mutators allow you to format template variables when retrieving them for rendering or setting their value.

If you have ever used [Eloquent mutators](http://laravel.com/docs/5.1/eloquent-mutators) for getting/setting data on an object it's the same concept (in fact it's almost the same code - thanks Laravel).

To define an accessor, create a `getFooAttribute` method in your class where `Foo` is the camel cased name of the variable you wish to access. See the `getFirstNameAttribute` method in the example above.

To define a mutator, create a `setFooAttribute` method in your class where `Foo` is the camel cased name of the variable you wish to change. See the `setFirstNameAttribute` method in the example above.

## Rendering engine

To use a different rendering engine pass an object that implements `Spin\Template\Engine\EngineInterface` to the `setEngine` method.

Support for Twig, Smarty, Plates and Dwoo are already included and can be implemented as per the examples below:

#### Twig
```php
class Homepage extends Spin\Template\Template
{
	protected $file = 'home.twig';

	public function __construct()
	{
		$loader = new Twig_Loader_Filesystem('/path/to/templates');
		$twig = new Twig_Environment($loader);

		$this->setEngine(new Spin\Template\Engine\TwigEngine($twig));
	}
}
```

#### Smarty
```php
class Homepage extends Spin\Template\Template
{
	protected $file = 'home.smarty';

	public function __construct()
	{
		$smarty = new Smarty();
		$smarty->setTemplateDir('/path/to/templates');
		$smarty->setCompileDir('/path/to/templates_c');

		$this->setEngine(new Spin\Template\Engine\SmartyEngine($smarty));
	}
}
```

#### Plates
```php
class Homepage extends Spin\Template\Template
{
	protected $file = 'home';

	public function __construct()
	{
		$plates = new League\Plates\Engine('/path/to/templates');

		$this->setEngine(new Spin\Template\Engine\PlatesEngine($plates));
	}
}
```

#### Dwoo
```php
class Homepage extends Spin\Template\Template
{
	protected $file = 'home.dwoo';

	public function __construct()
	{
		$dwoo = new Dwoo\Core();
		$dwoo->setTemplateDir('/path/to/templates');
		$dwoo->setCompileDir('/path/to/templates_c');

		$this->setEngine(new Spin\Template\Engine\DwooEngine($dwoo));
	}
}
```

## About

### Requirements

- Spin works with PHP 5.4 or above (excluding the requirements for any template engine you use)

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/fire015/spin/issues).

### License

Spin is licensed under the MIT License - see the `LICENSE.md` file for details.

### Acknowledgements

This library is heavily inspired by Laravel's Eloquent model class. Thanks guys!