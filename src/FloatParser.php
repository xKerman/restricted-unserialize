<?php

namespace xKerman\Restricted;

/**
 * format: http://php.net/manual/en/language.types.float.php
 *
 * <lnum> = 1*DIGIT
 * <dnum> = (*DIGIT "." <lnum>) / (<lnum> "." *DIGIT)
 * <exponent-dnum> = ["+" / "-"] (<lnum> | <dnum>) ["e" / "E"] ["+" / "-"] <lnum>
 */
class FloatParser implements ParserInterface
{
    public function parse(Source $source)
    {
        $source->consume('d');
        $source->consume(':');

        $result = '';
        if ($this->isSign($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }
        while (ctype_digit($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }

        if ($source->peek() === '.') {
            $result .= $source->peek();
            $source->next();
            while (ctype_digit($source->peek())) {
                $result .= $source->peek();
                $source->next();
            }
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
}
