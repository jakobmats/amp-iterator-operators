<?php
declare(strict_types = 1);

namespace AmpIteratorOperators\Operators;

use Amp\Iterator;
use AmpIteratorOperators\Internal\Assertions;

/**
 *
 * @author jakobmats
 *        
 */
class TakeOperator implements Operator
{
    use Assertions;

    /**
     *
     * @var int
     */
    private $selector;

    /**
     *
     * @param $selector int
     *            Must be a positive integer
     *            
     * @see \AmpIteratorOperators\Operators\Operator::__construct()
     */
    public function __construct(int $selector)
    {
        $this->assertNonNegativeInteger($selector);

        $this->selector = $selector;
    }

    /**
     *
     * {@inheritdoc}
     * @see \AmpIteratorOperators\Operators\Operator::__invoke()
     */
    public function __invoke(Iterator $iterator, callable $emit): \Generator
    {
        while ($this->selector > 0 && yield $iterator->advance()) {
            yield $emit($iterator->getCurrent());

            $this->selector --;
        }
    }
}

