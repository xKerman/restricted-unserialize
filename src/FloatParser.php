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

        $parser = new OptionalSignParser();
        list($sign, $source) = $parser->parse($source);
        $result .= $sign;

        if ($source->peek() === 'I') {
            return $this->parseInf($source, $sign);
        }

        list($dnum, $source) = $this->parseDnum($source);
        $result .= $dnum;

        list($exponential, $source) = $this->parseExponentialPart($source);
        $result .= $exponential;

        $source->consume(';');
        return [floatval($result), $source];
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
     * @param Source $source input
     * @param string $sign   '-', '+' or ''
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseInf($source, $sign)
    {
        if (!in_array($sign, ['', '-'], true)) {
            return $source->triggerError();
        }

        $source->consume('I');
        $source->consume('N');
        $source->consume('F');
        $source->consume(';');

        if ($sign === '-') {
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

    /**
     * parse integer part and fraction part
     *
     * @param Source $source input
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseDnum($source)
    {
        list($digits, $source) = $this->parseDigits($source);
        $result = $digits;
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
        return [$result, $source];
    }

    /**
     * parse given `$source` as exponentail part
     *
     * @param Source $source input
     * @return array
     * @throws UnserializeFailedException
     */
    private function parseExponentialPart($source)
    {
        if (strtolower($source->peek()) !== 'e') {
            return ['', $source];
        }

        $result = $source->peek();
        $source->next();
        $parser = new NumberStringParser();
        list($exp, $source) = $parser->parse($source);
        $result .= $exp;
        return [$result, $source];
    }
}
