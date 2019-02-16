<?php


namespace Zorrow\ThriftParser;

class ParserCal
{
    private $source = '';
    private $length = 0;
    private $offset = 0;

    public function __construct($path)
    {
        $this->source = file_get_contents($path);
        // remove comments, parser sometimes hags on it
        $this->source = preg_replace('~#[^\\n]*\\n~ui', "\n", $this->source);
        $this->source = preg_replace('~//[^\\n]*\\n~ui', "\n", $this->source);
        $this->length = \strlen($this->source);
    }


    public function offsetForward($offset = 0)
    {
        if (is_numeric($offset) && ($offset > 0)) {
            $this->offset += $offset;
        } else {
            $this->offset++;
        }
    }

    public function getOffsetChar($offset = 0)
    {
        $pos = 0;
        if (is_numeric($offset)) {
            $pos = $this->offset + $offset;
        }

        return $this->source[$pos] ?? '';
    }

    public function getOffsetStr($strlen)
    {
        return substr($this->source, $this->offset, $strlen);
    }

    public function isNotEOF($offset = 0): bool
    {
        return $this->offset + $offset < $this->length;
    }

    public function getCurrOffset()
    {
        return $this->offset;
    }

    public function getCurrLine(): int
    {
        return $this->getLineAt($this->offset);
    }

    public function getLineAt(int $pos): int
    {
        return mb_substr_count(
            substr($this->source, 0, $pos),
            "\n",
            'utf-8'
        );
    }

    public function setCurrOffset($offset)
    {
        $this->offset = $offset;
    }

    public function getLength()
    {
        return $this->length;
    }
}
