<?php

namespace Lang;

use ReflectionClass, ReflectionProperty;

/**
 * Trait to provide lazy initialization functionality for class properties.
 * 
 * @api
 * @since 1.0.0
 * @version 1.0.0
 * @package lazy-props
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
trait LazyProperties {

    /**
     * Constructor to initialize lazy properties.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     */
    public final function __construct() {

        foreach (static::getLazyInitializedProperties() as $propertyName => $_) {

            $this->unsetLazyInitializedProperty($propertyName);
        }
    }

    /**
     * Magic method to handle dynamic property access for lazy initialized properties.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     * @return mixed
     */
    public final function __get(string $name): mixed {

        $initializer = null;

        if (!is_null($specs = static::getLazyInitializedProperties()[$name] ?? null)) {

            $initializer = $specs->initializer ?? self::getInitializerMethod(new ReflectionClass($this))?->name;
    
            return $this->$name = $this->{$initializer}($name);
        }
        
        user_error(sprintf('Undefined property: %s::%s', static::class, $name));
        
        return null;
    }

    /**
     * Magic method to set a property value dynamically.
     * 
     * @api
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void {

        $this->$name = $value;
    }

    /**
     * Check if a property is lazy initialized.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     * @return bool
     */
    public final function isLazyInitializedProperty(string $name) {
        
        return !is_null(static::getLazyInitializedProperties()[$name] ?? null);
    }

    /**
     * Unsets a lazy initialized property.
     * 
     * @internal
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $propertyName
     * @return void
     */
    protected function unsetLazyInitializedProperty(string $propertyName): void {

        unset($this->$propertyName);
    }

    /**
     * Check if this class has a lazy initialized property.
     * 
     * @api
     * @final
     * @static
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     * @return bool
     */
    public static final function hasLazyProperty(string $name): bool {

        return !is_null(static::getLazyInitializedProperties()[$name] ?? null);
    }

    /**
     * Retrieves the lazy initialized properties of this class.
     * 
     * @final
     * @static
     * @internal
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @return array<Annotations\LazyInitialized>
     */
    protected static final function getLazyInitializedProperties(): array {

        $properties = (new ReflectionClass(static::class))->getProperties();

        return array_filter(array_combine(
            
            array_map(fn (ReflectionProperty $p) => $p->getName(), $properties),
            
            array_map(fn (ReflectionProperty $p) => Annotations\LazyInitialized::annotatedOn($p), $properties)
        ));
    }

    /**
     * Retrieves the initializer method for the lazy properties of this class.
     * 
     * @static
     * @internal
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param mixed $classReflection
     * @return Annotations\InitializerMethod|null
     */
    private static function getInitializerMethod(?ReflectionClass $classReflection): ?Annotations\InitializerMethod {

        return is_null($classReflection)
            ? null
            : Annotations\InitializerMethod::annotatedOn($classReflection)
                ?? self::getInitializerMethod(($parent = $classReflection->getParentClass()) === false ? null : $parent);
    }
}
