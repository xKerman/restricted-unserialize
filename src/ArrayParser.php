<?php

namespace xKerman\Restricted;

class ArrayParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $source->consume('a');
        $source->consume(':');

        $parser = new NumberParser();
        list($length, $source) = $parser->parse($source);
        if ($length < 0) {
            return $source->triggerError();
        }
        $result = [];

        $source->consume(':');
        $source->consume('{');

        for ($i = 0; $i < $length; ++$i) {
            list($key, $source) = $this->parseKey($source);
            list($value, $source) = $this->parseValue($source);
            $result[$key] = $value;
        }

        $source->consume('}');
        return [$result, $source];
    }

    private function parseKey($source)
    {
        switch ($source->peek()) {
            case 'i':
                $parser = new IntegerParser();
                break;
            case 's':
                $parser = new StringParser();
                break;
            default:
                return $source->triggerError();
        }
        return $parser->parse($source);
    }

    private function parseValue($source)
    {
        $parser = new ExpressionParser();
        return $parser->parse($source);
    }
}
