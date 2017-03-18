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
        $source->consume('d:');

        if ($source->peek() === 'N') {
            return $this->parseNan($source);
        }

        $parser = new OptionalSignParser();
        list($sign, $source) = $parser->parse($source);

        if ($source->peek() === 'I') {
            return $this->parseInf($source, $sign);
        }

        list($dnum, $source) = $this->parseDnum($source);
        $exponential = $source->match('/(?:[eE][+-]?[0-9]+)?/');
        $source->consume(';');

        return array(floatval(implode(array($sign, $dnum, $exponential))), $source);
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
        $source->consume('NAN;');
        return array(NAN, $source);
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
        if (!in_array($sign, array('', '-'), true)) {
            return $source->triggerError();
        }

        $source->consume('INF;');

        if ($sign === '-') {
            return array(-INF, $source);
        }
        return array(INF, $source);
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
        $result = $source->match('/\G([0-9]+\.[0-9]*|[0-9]*\.[0-9]+|[0-9]+)/');
        return array($result, $source);
    }
}
