<?php
/**
 * parser for PHP serialized boolean
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized boolean
 */
class BooleanParser implements ParserInterface
{
    /**
     * parse given `$source` as PHP serialized boolean
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $source->consume('b:');
        if (!$this->isBoolean($source->peek())) {
            return $source->triggerError();
        }
        $result = $source->peek();
        $source->next();
        $source->consume(';');

        return array((boolean)$result, $source);
    }

    /**
     * judge if given `$char` is boolean value or not
     *
     * @param string $char target
     * @return boolean
     */
    public function isBoolean($char)
    {
        return $char === '0' || $char === '1';
    }
}
