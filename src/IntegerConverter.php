<?php
/**
 * converter for integer
 */
namespace xKerman\Restricted;

/**
 * Converter for integer
 */
class IntegerConverter implements ConverterInterface
{
    /**
     * convert given input to integer
     *
     * @param string $input input for converter
     * @return integer
     */
    public function convert($input)
    {
        return intval($input, 10);
    }
}
