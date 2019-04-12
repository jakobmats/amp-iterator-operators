<?php
declare(strict_types = 1);

namespace AmpIteratorOperators\Operators;

use Amp\Iterator;

/**
 *
 * @author jakobmats
 *        
 */
class MapOperator implements Operator
{

    /**
     *
     * @var mixed
     */
    private $selector;

    /**
     *
     * @param mixed $selector
     *            Callable or an arbitrary value
     */
    public function __construct($selector)
    {
        $this->selector = $selector;
    }

    /**
     *
     * {@inheritdoc}
     * @see \AmpIteratorOperators\Operators\Operator::__invoke()
     */
    public function __invoke(Iterator $iterator, callable $emit): \Generator
    {
        while (yield $iterator->advance()) {
            if (is_callable($this->selector)) {
                yield $emit(($this->selector)($iterator->getCurrent()));
            } else {
                yield $emit($this->selector);
            }
        }
    }
}

