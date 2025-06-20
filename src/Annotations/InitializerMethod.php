<?php

namespace Lang\Annotations;

use Attraction\Annotation;

use Attribute;

/**
 * This annotation is used to specify a common initializer method for lazy properties in a class.
 * 
 * @api
 * @final
 * @since 1.0.0
 * @version 1.0.0
 * @package lazy-props
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
#[Attribute(Attribute::TARGET_CLASS)] final class InitializerMethod extends Annotation {

    /**
     * Creeates a new InitializerMethod annotation instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param string $name
     */
    public final function __construct(public readonly string $name = '__initialize') {}
}
