<?php

namespace xKerman\Restricted;

class ArrayParser implements ParserInterface {
    public function parse(Source $source) {
        $source->consume('a');
        $source->consume(':');

        $parser = new NumberParser();
        list($length, $source) = $parser->parse($source);
        $result = [];

        // TODO

        return [$result, $source];
    }
}