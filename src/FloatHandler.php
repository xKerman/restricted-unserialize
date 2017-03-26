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
class FloatHandler implements HandlerInterface
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
     * @param string $args   submatched
     * @return array
     * @throws UnserializeFailedException
     */
    public function handle(Source $source, $args)
    {
        if (array_key_exists($args, $this->mapping)) {
            return array($this->mapping[$args], $source);
        }
        return array(floatval($args), $source);
    }
}
