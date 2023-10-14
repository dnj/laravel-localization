# Laravel Localization
[![Latest Version on Packagist](https://img.shields.io/packagist/v/dnj/laravel-localization.svg?style=flat-square)](https://packagist.org/packages/dnj/laravel-localization)
[![Total Downloads](https://img.shields.io/packagist/dt/dnj/laravel-localization.svg?style=flat-square)](https://packagist.org/packages/dnj/laravel-localization)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
 

This package helps you to manage and present your multi-language Eloquent models easier.


There is core concept:
You have a record (like Product) and each record has some localized attribute (like title or descriptions).
So you create a Eloquent model for your main entity:
```php
<?php
namespace App\Models;

use dnj\Localization\Contracts\ITranslatableModel;
use dnj\Localization\Eloquent\HasTranslate;

class Product extends Model implements ITranslatableModel
{
	use HasTranslate;

	protected $table = "product";
}
```

And then create another model for its translates:

```php
<?php
namespace App\Models;

use dnj\Localization\Contracts\ITranslateModel;
use dnj\Localization\Eloquent\IsTranslate;

class ProductTranslate extends Model implements ITranslateModel
{
	use IsTranslate;

	protected $table = "product_translate";
}
```

## ITranslatableModel

This interface prodvide two methods for you which you can retrive translates for a model:  


| Method                                         | Description                                                    |
|------------------------------------------------|----------------------------------------------------------------|
| getTranslate(string $locale): ?ITranslateModel | Get translation for this model for `$locale`, if it's present. |
| getTranslates(): iterable<ITranslateModel>     | Get all translates for this model.                             |

To facilitate things there is a `HasTranslate` trait that taking care of implementing those methods.
On top of that, this trait has some extra methods:

| Method                                                               | Description                                                                      |
|----------------------------------------------------------------------|----------------------------------------------------------------------------------|
| getTranslateForUpdate(string $locale): ITranslateModel               | Get & lock the translation for update. If it's not exist an exception will throw |
| addTranslate(string $locale, array $fields): ITranslateModel         | Insert new translation for this object in `$locale` language.                    |
| deleteTranslate(string $locale): void                                | Permanently destroy a translate for this object in `$locale` language.           |
| updateTranslate(string $locale, array $fields): ITranslateModel      | Update the translation in `$locale` language for this object.                    |
| updateTranslate(array<string,array<string,string>> $changes): static | Insert&Update&Delete translations based on input.                                |

## ITranslateModel
This model is much simpler. Just contains some getters.

| Method                            | Description                                                                             |
|-----------------------------------|-----------------------------------------------------------------------------------------|
| getLocale(): string               | Getter for the translation `locale` value.                                              |
| getField(string $key): string     | Getter for the field with `$key` key. If `$key` does not exists an exception will throw |
| getFields(): array<string,string> | All fields as an associative array.                                                     |

And also there is `IsTranslate` trait which implements these methods for you.


## Security

If you've found a bug regarding security please mail [security@dnj.co.ir](mailto:security@dnj.co.ir) instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

