<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @license https://mit-license.org MIT License
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace Cubes\View\Helpers\Helpers\Factory\Holders;

use Illuminate\View\Compilers\Concerns\CompilesStacks;

class StyleHolder implements HolderInterface
{
    use CompilesStacks;

    /**
     * Default Style stackHolder Name where the all styles would be injected from buffered holders.
     *
     * @var string
     */
    protected $stackHolder = null;

    /**
     * Default script html element defined as holder for libraries/attributes injecting.
     *
     * @var string
     */
    protected $styleHolder = '<link rel="stylesheet" href="{{ asset(@path) }}">';

    /**
     * Default inline script html element sames as for scriptHolder.
     *
     * @var string
     */
    protected $inlineHolder = '<style>{@expression}</style>';

    /**
     * Defines stackHolder where to inject content from output buffer.
     *
     * @param  string $name
     * @return string
     */
    public function defineStackHolder($name)
    {
        if (empty($this->stackHolder)) {
            $this->stackHolder = "('{$name}')";
        }
    }
}