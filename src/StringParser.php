<?php

namespace xKerman\Restricted;

class StringParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $source->consume('s');
        $source->consume(':');
        $number = new NumberLiteralParser();
        list($length, $source) = $number->parse($source);
        if ($length < 0) {
            return $source->triggerError();
        }

        $source->consume(':');
        $source->consume('"');
        $result = '';
        for ($i = 0; $i < $length; ++$i) {
            $result .= $source->peek();
            $source->next();
        }
        $source->consume('"');
        $source->consume(';');

        return [$result, $source];
    }
}
