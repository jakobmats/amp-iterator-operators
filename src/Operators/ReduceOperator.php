<?php
declare(strict_types = 1);

namespace AmpIteratorOperators\Operators;

use Amp\Iterator;

/**
 *
 * @author jakobmats
 *        
 */
class ReduceOperator implements Operator
{

    /**
     *
     * @var callable
     */
    private $selector;

    /**
     *
     * @var mixed
     */
    private $seed;

    /**
     *
     * @var bool
     */
    private $hasSeed;

    /**
     *
     * @param callable $selector
     * @param mixed $seed
     *            Value to start the reducer with
     */
    public function __construct(callable $selector, $seed = null)
    {
        $this->selector = $selector;
        $this->seed = $seed;
        $this->hasSeed = $seed !== null;
    }

    /**
     *
     * {@inheritdoc}
     * @see \AmpIteratorOperators\Operators\Operator::__invoke()
     */
    public function __invoke(Iterator $iterator, callable $emit): \Generator
    {
        $hasAccumulation = false;
        $accumulation = null;

        while (yield $iterator->advance()) {
            if ($hasAccumulation) {
                $accumulation = ($this->selector)($accumulation, $iterator->getCurrent());
            } else {
                $x = $iterator->getCurrent();
                $accumulation = $this->hasSeed ? ($this->selector)($this->seed, $x) : $x;
                $hasAccumulation = true;
            }
        }

        yield $emit($accumulation);
    }
}

