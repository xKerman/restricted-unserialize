<?php

namespace xKerman\Restricted;

class NumberLiteralParser implements ParserInterface
{
    private $parser;

    public function __construct()
    {
        $this->parser = new NumberStringParser();
    }

    public function parse(Source $source)
    {
        list($result, $source) = $this->parser->parse($source);
        return [intval($result, 10), $source];
    }
}
