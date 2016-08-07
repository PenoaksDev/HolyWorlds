![logo](http://dev.holyworlds.org/images/logo.png "Holy Worlds Logo")

# Holy Worlds Development and Issue Tracker Repository

## About

This is the official issue tracker and development repository for Holy Worlds.

[The Official Website](http://holyworlds.org)

[The Development Website](http://dev.holyworlds.org)

This repository is administered by the Holy Worlds Administration and IT Team. But collaboration is open to anyone with PHP programming experience. Just follow the standard fork and pull request procedure. Changes are deployed to our production website every Monday at 2am, with the exception of manual deployments when patching of critical bugs is required.

Built using the [Milky Framework](https://github.com/PenoaksDev/Milky-Framework)

## Bug Reports

To encourage active collaboration, we strongly encourages pull requests, not just bug reports. "Bug reports" may also be sent in the form of a pull request containing a failing test.

However, if you file a bug report, your issue should contain a title and a clear description of the issue. You should also include as much relevant information as possible and a brief description that demonstrates the issue. Please do not submit duplicate issues. Screenshots are also highly appreciated when descriptions might be lacking. The goal of a bug report is to make it easy for yourself - and others - to replicate the bug and develop a fix.

Remember, bug reports are created in the hope that others with the same problem will be able to collaborate with you on solving it. Do not expect that the bug report will automatically see any activity or that others will jump to fix it. Creating a bug report serves to help yourself and others start on the path of fixing the problem.

## Development Discussion

You may propose new features or improvements of existing site behavior at the [issue tracker](https://github.com/PenoaksDev/HolyWorlds/issues) .

Informal discussion regarding bugs, new features, and implementation of existing features takes place in the IT Basement on our official website. Joel Greene, the head IT Admin of Holy Worlds, is typically available by PM (or by mentions) on weekdays from 10am-5pm (UTC-06:00 or America/Chicago), and sporadically present on the site at other times.

## Security Vulnerabilities

If you discover a security vulnerability within Holy Worlds, please send an e-mail to development@penoaks.com. All security vulnerabilities will be promptly addressed.

## PHP Coding Style

The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in RFC 2119.

* Files MUST use only <?php and <?= tags.

* Files MUST use only UTF-8 without BOM for PHP code.

* Files SHOULD either declare symbols (classes, functions, constants, etc.) or cause side-effects (e.g. generate output, change .ini settings, etc.) but SHOULD NOT do both.

* Namespaces and classes MUST follow an "autoloading" PSR: [PSR-0, PSR-4].

* Class names MUST be declared in StudlyCaps.

* Class constants MUST be declared in all upper case with underscore separators.

* Class MUST contain the MIT License before the class declaration.

* Method names MUST be declared in camelCase.

* Code MUST use tabs for indenting, no spaces unless used for minor alignment.

* There MUST NOT be a hard limit on line length; the soft limit MUST be 120 characters; lines SHOULD be 80 characters or less.

* There MUST be one blank line after the namespace declaration, and there MUST be one blank line after the block of use declarations.

* Opening braces for classes MUST go on the next line, and closing braces MUST go on the next line after the body.

* Opening braces for methods MUST go on the next line, and closing braces MUST go on the next line after the body.

* Opening braces for control structures MUST go on the next line, and closing braces MUST go on the next line after the body.

* Single line bodies SHOULD NOT contain parentheses.

* Opening parentheses for control structures MUST have a space after them, and closing parentheses for control structures MUST have a space before.

* Visibility MUST be declared on all properties and methods; abstract and final MUST be declared before the visibility; static MUST be declared after the visibility.

* Control structure keywords MUST have one space after them; method and function calls MUST NOT.

* Methods that are expected to return a value, MUST NOT end without a final return statement.

* All methods and properties MUST have PHPDoc

* PHPDoc MUST NOT declare `void` as a return type

* PHPDoc MUST contain a line return between @param, @return and @throws.

* PHPDoc MUST NOT use full namespaces (excludes starting ClassNames with a slash) and they must be present in USE statements

* Non-primitive array PHPDoc MUST define the ClassName followed by brackets.

### Example

```
<?php
namespace Vendor\Package;

use FooInterface;
use BarClass as Bar;
use OtherVendor\OtherPackage\BazClass;

/**
 * The MIT License (MIT)
 * Copyright 2016 Penoaks Publishing Co. <development@penoaks.org>
 *
 * This Source Code is subject to the terms of the MIT License.
 * If a copy of the license was not distributed with this file,
 * You can obtain one at https://opensource.org/licenses/MIT.
 */
class Foo extends Bar implements FooInterface
{
	const CONST_NAME = 0;

	/**
	 * Holds an array of BazClass
	 *
	 * BazClass[]
	 */
	private $anArray = [];

	/**
	 * A sample method
	 *
	 * @param string $a
	 * @param SomeObject $b
	 * 
	 * @return string|null
	 *
	 * @throws \Exception
	 */
	public function sampleFunction( $a, SomeObject $b = null )
	{
		if ( $a === $b )
			bar();
		else if ( $a > $b )
			$foo->bar( $arg1 );
		else
		{
			BazClass::bar( $arg2, $arg3 );
			return $arg3;
		}
		
		[...]
		
		return null;
	}

	/**
	 * Another PHPDoc block with no attributes
	 */
	final public static function bar()
	{
		// method body
	}
}
```
