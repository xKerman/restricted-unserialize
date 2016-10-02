<?php

namespace xKerman\Restricted;

class ExpressionParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $parser = $this->createParser($source);
        list($result, $source) = $parser->parse($source);
        return [$result, $source];
    }

    private function createParser($source)
    {
        switch ($source->peek()) {
            case 'N':
                return new NullParser();
            case 'b':
                return new BooleanParser();
            case 'i':
                return new IntegerParser();
            case 'd':
                return new FloatParser();
            case 's':
                return new StringParser();
            case 'a':
                return new ArrayParser();
            default:
                return $source->triggerError();
        }
    }
}
