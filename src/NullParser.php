<?php

namespace xKerman\Restricted;

class NullParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $source->consume('N');
        $source->consume(';');
        return [null, $source];
    }
}
