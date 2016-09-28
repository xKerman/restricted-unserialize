<?php

namespace xKerman\Restricted;

function unserialize($str) {
    $source = new Source($str);
    switch ($source->peek()) {
    case 'N':
        $parser = new NullParser();
        list($result, $source) = $parser->parse($source);
        return $result;
    case 'b':
        $parser = new BooleanParser();
        list($result, $source) = $parser->parse($source);
        return $result;
    case 'i':
        $parser = new IntegerParser();
        list($result, $source) = $parser->parse($source);
        return $result;
    case 'd':
        $parser = new FloatParser();
        list($result, $source) = $parser->parse($source);
        return $result;
    case 's':
        $parser = new StringParser();
        list($result, $source) = $parser->parse($source);
        return $result;
    case 'a':
        $parser = new ArrayParser();
        list($result, $source) = $parser->parse($source);
        return $result;
    default:
        return false;
    }
}
