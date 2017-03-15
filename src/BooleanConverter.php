<?php
/**
 * converter for boolean
 */
namespace xKerman\Restricted;

/**
 * Converter for boolean
 */
class BooleanConverter implements ConverterInterface
{
    /**
     * convert given input to boolean
     *
     * @param string $input input for converter
     * @return boolean
     */
    public function convert($input)
    {
        return (boolean)$input;
    }
}
