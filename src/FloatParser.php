<?php
/**
 * parser for PHP serialized float number
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized float number
 *
 * format: http://php.net/manual/en/language.types.float.php
 */
class FloatParser implements ParserInterface
{
    /**
     * parse given `$source` as PHP serialized float number
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $source->consume('d');
        $source->consume(':');

        $result = '';

        if ($source->peek() === 'N') {
            return $this->parseNan($source);
        }

        if ($this->isSign($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }

        if ($source->peek() === 'I') {
            if ($result === '+') {
                return $source->triggerError();
            }
            return $this->parseInf($source, ($result === '-'));
        }

        list($digits, $source) = $this->parseDigits($source);
        $result .= $digits;
        $hasIntegerPart = ($digits !== '');

        $hasFractionPart = false;
        if ($source->peek() === '.') {
            $result .= $source->peek();
            $source->next();
            list($digits, $source) = $this->parseDigits($source);
            $result .= $digits;
            $hasFractionPart = ($digits !== '');
        }

        if (!$hasIntegerPart && !$hasFractionPart) {
            return $source->triggerError();
        }

        if (strtolower($source->peek()) === 'e') {
            $result .= $source->peek();
            $source->next();
            $parser = new NumberStringParser();
            list($exp, $source) = $parser->parse($source);
            $result .= $exp;
        }

        $source->consume(';');
        return [floatval($result), $source];
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

    /**
     * parse given `$source` as NAN
     *
     * @param Source $source input
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseNan($source)
    {
        $source->consume('N');
        $source->consume('A');
        $source->consume('N');
        $source->consume(';');
        return [NAN, $source];
    }

    /**
     * parse given `$source` as INF / -INF
     *
     * @param Source  $source input
     * @param boolean $minus  target is negative or not
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseInf($source, $minus)
    {
        $source->consume('I');
        $source->consume('N');
        $source->consume('F');
        $source->consume(';');

        if ($minus) {
            return [-INF, $source];
        }
        return [INF, $source];
    }

    /**
     * parse given `$source` as sequence of DIGIT
     *
     * @param Source $source input
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseDigits($source)
    {
        $result = '';
        while (ctype_digit($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }
        return [$result, $source];
    }
}
