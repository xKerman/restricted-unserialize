<?php
/**
 * provide interface for Converter
 */
namespace xKerman\Restricted;

/**
 * Interface for Converter
 */
interface ConverterInterface
{
    /**
     * convert type for given `$input`
     *
     * @param string $input converter input
     * @return mixed converter result
     */
    public function convert($input);
}
