# Albums

The Albums module (`images.albums`) introduces the "Albums" content type to the CMS
[Icybee](http://icybee.org). It allows images to be orginized as albums.





## Requirement

The package requires PHP 5.3 or later.





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/).
Create a `composer.json` file and run `php composer.phar install` command to install it:

```json
{
	"minimum-stability": "dev",
	"require":
	{
		"icybee/module-images-albums": "*"
	}
}
```





### Cloning the repository

The package is [available on GitHub](https://github.com/Icybee/module-images-albums), its repository can be
cloned with the following command line:

	$ git clone git://github.com/Icybee/module-images-albums.git images.albums





## Documentation

The documentation for the package and its dependencies can be generated with the `make doc`
command. The documentation is generated in the `docs` directory using [ApiGen](http://apigen.org/).
The package directory can later by cleaned with the `make clean` command.





## Testing

The test suite is ran with the `make test` command. [Composer](http://getcomposer.org/) is
automatically installed as well as all the dependencies required to run the suite. The package
directory can later be cleaned with the `make clean` command.

The package is continuously tested by [Travis CI](http://about.travis-ci.org/).

[![Build Status](https://travis-ci.org/Icybee/modules-images-albums.png?branch=master)](https://travis-ci.org/Icybee/modules-images-albums)





## License

The module is licensed under the New BSD License - See the LICENSE file for details.