Spin Template
==========

[![Total Downloads](https://img.shields.io/packagist/dm/spin/template.svg)](https://packagist.org/packages/spin/template)
[![Build Status](https://travis-ci.org/fire015/spin.svg?branch=master)](https://travis-ci.org/fire015/spin)

An Active Record like pattern for template rendering with PHP - supporting Twig, Smarty, Plates and Dwoo.

### Installation

Install Spin via [composer](http://getcomposer.org/). Then extend `Spin\Template\Template`.

```
composer require spin/template
```

### Examples

If you have ever used [Eloquent mutators](http://laravel.com/docs/5.1/eloquent-mutators) for getting/setting data on an object it's the same concept (in fact it's almost the same code - thanks Laravel).

Here is a basic example using the default rendering engine:

```php
class Homepage extends Spin\Template\Template
{
	/**
	 * The template file
	 */
	protected $file = 'templates/home.php';

	/**
	 * Get the user's name
	 */
    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

	/**
	 * Set the user's name
	 */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

	/**
	 * Convert the unix timestamp to a human friendly date
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

// Assign variables as an array
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

As you can see we assign variables to our class and call the `render()` method, which passes those variables to our template defined in `$file`.

#### Accessors and mutators

Accessors and mutators allow you to format template variables when retrieving them for rendering or setting their value.

To define an accessor, create a `getFooAttribute` method in your class where `Foo` is the camel cased name of the variable you wish to access.

To define a mutator, create a `setFooAttribute` method in your class where `Foo` is the camel cased name of the variable you wish to change.

### Rendering