<?php

namespace xKerman\Restricted;

class IntegerParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $source->consume('i');
        $source->consume(':');
        $parser = new NumberLiteralParser();
        list($result, $source) = $parser->parse($source);
        $source->consume(';');

        return [$result, $source];
    }
}
