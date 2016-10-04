<?php
/**
 * parser for number
 */
namespace xKerman\Restricted;

/**
 * Parser for number, return result as string
 *
 * <number> = ["-" / "+"] 1*DIGIT
 * note that leading zeros are valid (e.g. "0089" is valid)
 */
class NumberStringParser implements ParserInterface
{
    /**
     * parse given `$source` as number
     *
     * @param Source $source parser input
     * @return string
     */
    public function parse(Source $source)
    {
        $result = '';
        if ($this->isSign($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }

        $hasDigit = false;
        while (ctype_digit($source->peek())) {
            $hasDigit = true;
            $result .= $source->peek();
            $source->next();
        }
        if (!$hasDigit) {
            return $source->triggerError();
        }

        return [$result, $source];
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
