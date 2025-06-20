<?php

namespace Lang\Annotations;

use Attraction\Annotation;

use Attribute;

/**
 * This annotation is used to name the readonly properties that are lazily initialized in subclasses.
 * 
 * @api
 * @final
 * @since 1.0.0
 * @version 1.0.0
 * @package lazy-props
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
#[Attribute(Attribute::TARGET_CLASS)] final class ExtendedLazyProperties extends Annotation {

    /**
     * The names of the properties that are lazily initialized in subclasses.
     * 
     * @api
     * @since 1.0.0
     * 
     * @var array<string> $properties
     */
    public readonly array $properties;

    /**
     * Creates a new ExtendedLazyProperties annotation instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string ...$properties
     */
    public final function __construct(string $property, string ...$properties) {

        $this->properties = array_merge([$property], $properties);
    }
}
