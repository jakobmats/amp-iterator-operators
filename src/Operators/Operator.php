<?php
declare(strict_types = 1);

namespace AmpIteratorOperators\Operators;

use Amp\Iterator;

/**
 *
 * @author jakobmats
 */
interface Operator
{

    /**
     *
     * @param Iterator $iterator
     *            Target iterator
     * @param callable $emit
     *            Emit function used to feed values to an emitter object
     * @return \Generator
     */
    public function __invoke(Iterator $iterator, callable $emit): \Generator;
}
