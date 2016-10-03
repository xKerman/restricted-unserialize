<?php

namespace xKerman\Restricted;

class BooleanParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $source->consume('b');
        $source->consume(':');
        if (!$this->isBoolean($source->peek())) {
            return $source->triggerError();
        }
        $result = $source->peek();
        $source->next();
        $source->consume(';');

        return [(boolean)$result, $source];
    }

    public function isBoolean($char)
    {
        return $char === '0' || $char === '1';
    }
}
