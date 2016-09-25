<?php

namespace xKerman\Restricted;

function unserialize($str) {
    $source = new Source($str);
    $symbol = $source->readSymbol();
    switch ($symbol) {
    case 'N':
        return null;
    case 'b':
        return $source->readBoolean();
    case 'i':
        return $source->readInteger();
    case 'd':
        return $source->readDouble();
    case 's':
        return $source->readString();
    case 'a':
        return $source->readArray();
    default:
        return false;
    }
}
