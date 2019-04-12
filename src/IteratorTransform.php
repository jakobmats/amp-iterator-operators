<?php
declare(strict_types = 1);

namespace AmpIteratorOperators;

use Amp\Iterator;
use Amp\Producer;
use AmpIteratorOperators\Operators\MapOperator;
use AmpIteratorOperators\Operators\Operator;
use AmpIteratorOperators\Operators\TakeOperator;
use AmpIteratorOperators\Operators\ReduceOperator;
use function Amp\call;
use AmpIteratorOperators\Operators\ZipOperator;

/**
 *
 * @author jakobmats
 *        
 */
class IteratorTransform
{

    /**
     *
     * @var \Amp\Iterator
     */
    private $iterator;

    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     *
     * @see \AmpIteratorOperators\Operators\MapOperator
     * @param mixed $selector
     * @return self
     */
    public function map($selector): self
    {
        $map = new MapOperator($selector);

        return $this->apply($map);
    }

    /**
     *
     * @see \AmpIteratorOperators\Operators\TakeOperator
     * @param int $selector
     * @return self
     */
    public function take(int $selector): self
    {
        $take = new TakeOperator($selector);

        return $this->apply($take);
    }

    /**
     *
     * @see \AmpIteratorOperators\Operators\ReduceOperator
     * @param callable $selector
     * @param mixed $seed
     * @return self
     */
    public function reduce(callable $selector, $seed = null): self
    {
        $reduce = new ReduceOperator($selector, $seed);

        return $this->apply($reduce);
    }

    /**
     *
     * @see \AmpIteratorOperators\Operators\ZipOperator
     * @param array $iterators
     * @param callable $selector
     * @return self
     */
    public function zip(array $iterators, ?callable $selector = null): self
    {
        $zip = new ZipOperator($iterators, $selector);

        return $this->apply($zip);
    }

    /**
     * Final processed iterator
     *
     * @return Iterator
     */
    public function iterate(): Iterator
    {
        return $this->iterator;
    }

    /**
     *
     * @internal
     * @param Operator $operator
     * @return self
     */
    private function apply(Operator $operator): self
    {
        return new self(new Producer(function (callable $emit) use ($operator) {
            yield from $operator($this->iterator, $emit);
        }));
    }
}
