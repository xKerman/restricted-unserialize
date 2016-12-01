<?php
/**
 * parser for serialized string
 */
namespace xKerman\Restricted;

/**
 * Parser class for parse serialized PHP stirng
 */
class StringParser implements ParserInterface
{
    /**
     * parse give `$source` as PHP serialized string
     *
     * @param Source $source parser input
     * @return array parser result
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $source->consume('s:');

        $parser = new LengthParser();
        list($length, $source) = $parser->parse($source);

        $source->consume(':"');
        $result = '';
        for ($i = 0; $i < $length; ++$i) {
            $result .= $source->peek();
            $source->next();
        }
        $source->consume('";');

        return array($result, $source);
    }
}
