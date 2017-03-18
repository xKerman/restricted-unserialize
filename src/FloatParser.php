<?php
/**
 * parser for PHP serialized float number
 */
namespace xKerman\Restricted;

/**
 * Parser for PHP serialized float number
 *
 * format: http://php.net/manual/en/language.types.float.php
 */
class FloatParser implements ParserInterface
{
    /** @var array $mapping parser result mapping */
    private $mapping;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->mapping = array(
            'INF'  => INF,
            '-INF' => -INF,
            'NAN'  => NAN,
        );
    }

    /**
     * parse given `$source` as PHP serialized float number
     *
     * @param Source $source parser input
     * @return array
     * @throws UnserializeFailedException
     */
    public function parse(Source $source)
    {
        $value = $source->match(
            '/\Gd:((?:[+-]?(?:[0-9]+\.[0-9]*|[0-9]*\.[0-9]+|[0-9]+)(?:[eE][+-]?[0-9]+)?)|-?INF|NAN);/'
        );
        if (array_key_exists($value, $this->mapping)) {
            return array($this->mapping[$value], $source);
        }
        return array(floatval($value), $source);
    }
}
