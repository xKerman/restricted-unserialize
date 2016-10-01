<?php

namespace xKerman\Restricted;

/**
 * format: http://php.net/manual/en/language.types.float.php
 */
class FloatParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $source->consume('d');
        $source->consume(':');

        $result = '';

        if ($source->peek() === 'N') {
            return $this->parseNan($source);
        }

        if ($this->isSign($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }

        if ($source->peek() === 'I') {
            if ($result === '+') {
                $source->triggerError();
                return;
            }
            return $this->parseInf($source, ($result === '-'));
        }

        $hasIntegerPart = false;
        while (ctype_digit($source->peek())) {
            $result .= $source->peek();
            $source->next();
            $hasIntegerPart = true;
        }

        $hasFractionPart = false;
        if ($source->peek() === '.') {
            $result .= $source->peek();
            $source->next();
            while (ctype_digit($source->peek())) {
                $result .= $source->peek();
                $source->next();
                $hasFractionPart = true;
            }
        }

        if (!$hasIntegerPart && !$hasFractionPart) {
            $source->triggerError();
            return;
        }

        if (strtolower($source->peek()) === 'e') {
            $result .= $source->peek();
            $source->next();
            $parser = new NumberParser(true);
            list($exp, $source) = $parser->parse($source);
            $result .= $exp;
        }

        $source->consume(';');
        return [floatval($result), $source];
    }

    private function isSign($char)
    {
        return $char === '+' || $char === '-';
    }

    private function parseNan($source)
    {
        $source->consume('N');
        $source->consume('A');
        $source->consume('N');
        $source->consume(';');
        return [NAN, $source];
    }

    private function parseInf($source, $minus)
    {
        $source->consume('I');
        $source->consume('N');
        $source->consume('F');
        $source->consume(';');

        if ($minus) {
            return [-INF, $source];
        }
        return [INF, $source];
    }
}
