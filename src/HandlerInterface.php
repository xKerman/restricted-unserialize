<?php
/**
 * provide interface for Handler
 */
namespace xKerman\Restricted;

/**
 * Interface for Handler
 */
interface HandlerInterface
{
    /**
     * parse given `$source`
     *
     * @param Source $source parser input
     * @param mixed  $args   submatched
     * @return array parse result
     * @throws UnserializeFailedException
     */
    public function handle(Source $source, $args);
}
