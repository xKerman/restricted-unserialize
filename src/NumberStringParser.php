<?php
/**
 * parser for number
 */
namespace xKerman\Restricted;

/**
 * Parser for number, return result as string
 *
 * <number> = array("-" / "+") 1*DIGIT
 * note that leading zeros are valid (e.g. "0089" is valid)
 */
class NumberStringParser implements ParserInterface
{
    /**
     * parse given `$source` as number
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $result = $source->match('/\G[+-]?[0-9]+/');
        if ($result === '') {
            return $source->triggerError();
        }
        return array($result, $source);
    }
}
