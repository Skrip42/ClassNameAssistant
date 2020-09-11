<?php
namespace Skrip42\ClassName;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;

class ClassName
{
    /** @var Inflector|null */
    private static $inflector;

    private $className;
    private $namespace;

    public function __construct(string $className)
    {
        if (strrpos($className, '\\') !== false) {
            $this->className = substr($className, strrpos($className, '\\') + 1);
            $this->namespace = substr($className, 0, strrpos($className, '\\'));
        } else {
            $this->className = $className;
        }

        if (null === static::$inflector) {
            static::$inflector = InflectorFactory::create()->build();
        }
    }

    public static function from(string $className) : ClassName
    {
        return new ClassName($className);
    }

    public function getName() : string
    {
        return $this->namespace . '\\' . $this->className;
    }

    public function getNamespace() : string
    {
        return $this->namespace;
    }

    public function getShortName() : string
    {
        return $this->className;
    }

    public function toCamelCase() : self
    {
        $this->className = strtr(
            ucwords(
                strtr(
                    $this->className,
                    ['_' => ' ', '.' => ' ', '\\' => ' ']
                )
            ),
            [' ' => '']
        );
        return $this;
    }

    public function toSnakeCase() : self
    {
        $this->className = preg_replace('/(?<=\\w)([A-Z])/', '_$1', $this->className);
        $this->className = preg_replace('/_{2,}/', '_', $this->className);
        $this->className = strtolower($this->className);

        return $this;
    }

    public function isCamelCase() : bool
    {
        return strpos($this->className, '_') === false;
    }

    public function isSnakeCase() : bool
    {
        return strpos($this->className, '_') !== false
            || (bool) !preg_match('[A-Z]', $this->className);
    }

    public function toPlural() : self
    {
        $this->modLastWord(function ($word) {
            return self::$inflector->pluralize($word);
        });
        return $this;
    }

    public function toSingular() : self
    {
        $this->modLastWord(function ($word) {
            return self::$inflector->singularize($word);
        });
        return $this;
    }

    private function modLastWord($callback)
    {
        if ($this->isCamelCase()) {
            $parts = preg_split('/(?=[A-Z])/', $this->className);
            $parts[count($parts) - 1]
                = $callback($parts[count($parts) - 1]);
            $this->className = implode('', $parts);
        } else if ($this->isSnakeCase()) {
            $parts = explode('_', $this->className);
            $parts[count($parts) - 1]
                = $callback($parts[count($parts) - 1]);
            $this->className = implode('_', $parts);
        }
    }

    public function toLower() : self
    {
        $this->className = lcfirst($this->className);
        return $this;
    }

    public function toUpper() : self
    {
        $this->className = ucfirst($this->className);
        return $this;
    }
}
