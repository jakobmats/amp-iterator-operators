<?php

namespace AmpIteratorOperators;

use Amp\Iterator;

/**
 * Factory function for creating IteratorTransform object
 *
 * @param Iterator $iterator
 * @return IteratorTransform
 */
function transform(Iterator $iterator): IteratorTransform
{
    return new IteratorTransform($iterator);
}
