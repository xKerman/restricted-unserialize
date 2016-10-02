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
                return $source->triggerError();
            }
            return $this->parseInf($source, ($result === '-'));
        }

        $hasIntegerPart = false;
        list($digits, $source) = $this->parseDigits($source);
        $result .= $digits;
        $hasIntegerPart = ($digits !== '');

        $hasFractionPart = false;
        if ($source->peek() === '.') {
            $result .= $source->peek();
            $source->next();
            list($digits, $source) = $this->parseDigits($source);
            $result .= $digits;
            $hasFractionPart = ($digits !== '');
        }

        if (!$hasIntegerPart && !$hasFractionPart) {
            return $source->triggerError();
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

    private function parseDigits($source)
    {
        $result = '';
        while (ctype_digit($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }
        return [$result, $source];
    }
}
