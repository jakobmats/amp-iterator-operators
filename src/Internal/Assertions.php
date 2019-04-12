<?php
declare(strict_types = 1);

namespace AmpIteratorOperators\Internal;

/**
 *
 * @author jakobmats
 * @internal
 *
 */
trait Assertions
{

    /**
     *
     * @param int $arg
     * @throws \InvalidArgumentException
     */
    public function assertNonNegativeInteger(int $arg)
    {
        if ($arg < 0) {
            throw new \InvalidArgumentException('Selector must be a non-negative integer. Got ' . $arg . ' instead.');
        }
    }

    /**
     *
     * @param array $array
     * @param string $type
     * @throws \InvalidArgumentException
     */
    public function assertArrayOf(array $array, string $type)
    {
        foreach ($array as $element) {
            if (! ($element instanceof $type) && \gettype($element) !== $type) {
                throw new \InvalidArgumentException($this->typeAssertionMessage("All elements must be of type $type", $element));
            }
        }
    }

    /**
     *
     * @param string $notice
     * @param mixed $arg
     * @return string
     */
    private function typeAssertionMessage(string $notice, $arg): string
    {
        $type = \gettype($arg);

        return "$notice. Got $type instead.";
    }
}

