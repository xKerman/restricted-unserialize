<?php

namespace xKerman\Restricted;

function unserialize($str)
{
    $source = new Source($str);
    $parser = new ExpressionParser();
    list($result, $source) = $parser->parse($source);
    return $result;
}
