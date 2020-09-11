# ClassNameAssistant
class name assistant top of doctrine/inflector
## install:
`composer require skrip42/class-name`
## usage:
```php
//create class name instance
$className = new ClassName('Skrip42\ClassName');
//or
$className = ClassName::from('Skrip42\ClassName');

//get data from ClassName instance
$className->getShortName(); //return 'ClassName';
$className->getNamespace(); //return 'Skrip42';
$className->getName(); //return 'Skrip42\ClassName';

//convert ClassName data
$className->toPlural()->getShortName(); //return 'ClassNames'
$className->toSingular()->getShortName(); // return 'ClassName'
$className->toSnakeCase()->getShortName(); // reutrn class_name
$className->toCamelCase()->getShortName(); // return 'ClassName'
$className->toLower()->getShortName(); // return 'className'
$className->toUpper()->getShortName(); // return 'ClassName'

```
