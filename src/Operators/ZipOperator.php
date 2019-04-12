<?php

namespace AmpIteratorOperators\Operators;

use Amp\Iterator;
use AmpIteratorOperators\Internal\Assertions;
use function Amp\Promise\all;

/**
 *
 * @author jakobmats
 *        
 */
class ZipOperator implements Operator
{
    use Assertions;

    /**
     *
     * @var Array<\Amp\Iterator>
     */
    private $iterators;

    /**
     *
     * @var ?callable
     */
    private $selector;

    /**
     *
     * @param array $iterators
     * @param callable $selector
     */
    public function __construct(array $iterators, ?callable $selector = null)
    {
        $this->assertArrayOf($iterators, Iterator::class);

        $this->iterators = $iterators;
        $this->selector = $selector;
    }

    /**
     *
     * {@inheritdoc}
     * @see \AmpIteratorOperators\Operators\Operator::__invoke()
     */
    public function __invoke(Iterator $iterator, callable $emit): \Generator
    {
        array_unshift($this->iterators, $iterator);

        $advanceAll = function (Iterator $it) {
            return $it->advance();
        };
        $getAllValues = function (Iterator $it) {
            return $it->getCurrent();
        };

        // Iterators need to be synchronized with \Amp\Promise\all
        while (! in_array(false, yield all(array_map($advanceAll, $this->iterators)), true)) {
            $mapped = array_map($getAllValues, $this->iterators);

            if (! is_null($this->selector)) {
                yield $emit(($this->selector)(...$mapped));
            } else {
                yield $emit($mapped);
            }
        }
    }
}

