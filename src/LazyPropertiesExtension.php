<?php

namespace Lang;

use ReflectionClass;

/**
 * This trait allows the use of readonly properties that are lazily initialized in subclasses.
 * 
 * @api
 * @since 1.0.0
 * @version 1.0.0
 * @package lazy-props
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
trait LazyPropertiesExtension {

    /**
     * Magic method to set a property value dynamically.
     * 
     * @api
     * @override
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void {

        if (self::hasExtendedLazyProperty($name)) {

            $this->$name = $value;
        } else {

            parent::__set($name, $value);
        }
    }

    /**
     * Unsets a lazy initialized property.
     * 
     * @override
     * @internal
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $propertyName
     * @return void
     */
    protected function unsetLazyInitializedProperty(string $propertyName): void {

        if (self::hasExtendedLazyProperty($propertyName)) {

            unset($this->$propertyName);
        } else {

            parent::unsetLazyInitializedProperty($propertyName);
        }
    }

    /**
     * Check if this class has an extended lazy initialized property.
     * 
     * @api
     * @static
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     * @return bool
     */
    public static function hasExtendedLazyProperty(string $name): bool {

        return in_array($name, Annotations\ExtendedLazyProperties::annotatedOn(new ReflectionClass(self::class))?->properties ?? []);
    }
}
