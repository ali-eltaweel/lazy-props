<?php

namespace Lang\Annotations;

use Attraction\Annotation;

use Attribute;

/**
 * This annotation is used to mark properties that are lazily initialized.
 * 
 * @api
 * @final
 * @since 1.0.0
 * @version 1.0.0
 * @package lazy-props
 * @author Ali M. Kamel <ali.kamel.dev@gmail.com>
 */
#[Attribute(Attribute::TARGET_PROPERTY)] final class LazyInitialized extends Annotation {

    /**
     * Creates a new LazyInitialized annotation instance.
     * 
     * @api
     * @final
     * @since 1.0.0
     * @version 1.0.0
     * 
     * @param ?string $initializer
     */
    public final function __construct(public readonly ?string $initializer = null) {}
}
