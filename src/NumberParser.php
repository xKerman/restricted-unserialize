<?php

namespace xKerman\Restricted;

class NumberParser implements ParserInterface
{
    private $returnString;

    /**
     * @param boolean $withSign
     */
    public function __construct($returnString = false)
    {
        $this->returnString = $returnString;
    }

    /**
     * @return int
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
            $source->triggerError();
            return;
        }

        if ($this->returnString) {
            return [$result, $source];
        } else {
            return [intval($result, 10), $source];
        }
    }

    private function isSign($char)
    {
        return $char === '+' || $char === '-';
    }
}
