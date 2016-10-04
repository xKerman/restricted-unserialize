<?php

/**
 * parser for optional sign
 */
namespace xKerman\Restricted;

/**
 * Parser for optional sign
 */
class OptionalSignParser implements ParserInterface
{
    /**
     * parse given `$source` and return sign if exist
     *
     * @param Source $source parser input
     * @return array
     */
    public function parse(Source $source)
    {
        if (!$this->isSign($source->peek())) {
            return ['', $source];
        }
        $sign = $source->peek();
        $source->next();
        return [$sign, $source];
    }

    /**
     * judge if given `$char` is minus|plus sign
     *
     * @param string $char target character
     * @return boolean
     */
    private function isSign($char)
    {
        return $char === '+' || $char === '-';
    }
}
