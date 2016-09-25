<?php

namespace xKerman\Restricted;

class Source {
    private $str;

    private $current;

    public function __construct($str) {
        $this->str = $str;
        $this->current = 0;
    }

    public function readSymbol() {
        $result = substr($this->str, $this->current, 1);
        ++$this->current;
        return $result;
    }

    public function readBoolean() {
        $this->expect(':');
        $value = $this->readUntil(';');
        $this->expect(';');
        if ($value === '0') {
            return false;
        } elseif ($value === '1') {
            return true;
        }
        $this->triggerError();
    }

    public function readInteger() {
        $this->expect(':');
        $value = $this->readUntil(';');
        $this->expect(';');
        return $this->convertAsInteger($value);
    }

    public function readDouble() {
        $this->expect(':');
        $value = $this->readUntil(';');
        $this->expect(';');
        $double = '(?:[-+]?
            (?:
                (?:[0-9]+(?:\.(?:[0-9]+)?)?)
                |
                (?:\.[0-9]+)
            )
        )';
        $exponent = "(?:{$double}[eE]$double)";
        $regexp = '/\A(?:' . $double . '|' . $exponent . ')\z/x';
        if (!preg_match($regexp, $value)) {
            var_dump($regexp);
            var_dump($value);
            $this->triggerError();
            return;
        }
        return doubleval($value);
    }

    public function readString() {
        $this->expect(':');
        $length = $this->convertAsInteger($this->readUntil(':'));
        $this->expect(':');
        $this->expect('"');
        $result = $this->read($length);
        $this->expect('"');
        $this->expect(';');

        return $result;
    }

    public function readArray() {
        return [];
    }

    private function read($length) {
        if (strlen($this->str) < $this->current + $length) {
            $this->triggerError();
            return;
        }

        $result = substr($this->str, $this->current, $length);
        $this->current += $length;
        return $result;
    }

    private function expect($expected) {
        $actual = $this->read(1);
        if ($actual !== $expected) {
            $this->triggerError();
        }
    }

    private function readUntil($end) {
        $pos = strpos($this->str, $end, $this->current);
        if ($pos === false) {
            $this->triggerError();
            return;
        }
        return $this->read($pos - $this->current);
    }

    private function triggerError() {
        $bytes = strlen($this->str);
        trigger_error("unserialize(): Error at offset {$this->current} of {$bytes} bytes");
    }

    private function convertAsInteger($value) {
        if (!preg_match('/\A[-+]?[0-9]+\z/', $value)) {
            $this->triggerError();
            return;
        }
        return intval($value, 10);
    }
}
