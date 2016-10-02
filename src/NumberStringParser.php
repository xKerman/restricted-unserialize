<?php

namespace xKerman\Restricted;

class NumberStringParser implements ParserInterface
{
    /**
     * @return string
     */
    public function parse(Source $source)
    {
        $result = '';
        if ($this->isSign($source->peek())) {
            $result .= $source->peek();
            $source->next();
        }

        $hasDigit = false;
        while (ctype_digit($source->peek())) {
            $hasDigit = true;
            $result .= $source->peek();
            $source->next();
        }
        if (!$hasDigit) {
            return $source->triggerError();
        }

        return [$result, $source];
    }

    private function isSign($char)
    {
        return $char === '+' || $char === '-';
    }
}
