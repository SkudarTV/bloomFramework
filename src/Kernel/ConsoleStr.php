<?php

namespace Bloom\Kernel;

use Stringable;

class ConsoleStr implements Stringable
{
    const string TEXT_BLACK = '0;30';
    const string TEXT_WHITE = '1;37';
    const string TEXT_DARK_GREY = '1;30';
    const string TEXT_RED = '0;31';
    const string TEXT_GREEN = '0;32';
    const string TEXT_BROWN = '0;33';
    const string TEXT_YELLOW = '1;33';
    const string TEXT_BLUE = '0;34';
    const string TEXT_MAGENTA = '0;35';
    const string TEXT_CYAN = '0;36';
    const string TEXT_LIGHT_CYAN = '1;36';
    const string TEXT_LIGHT_GREY = '0;37';
    const string TEXT_LIGHT_RED = '1;31';
    const string TEXT_LIGHT_GREEN = '1;32';
    const string TEXT_LIGHT_BLUE = '1;34';
    const string TEXT_LIGHT_MAGENTA = '1;35';

    const string BG_BLACK = '40';
    const string BG_RED = '41';
    const string BG_GREEN = '42';
    const string BG_YELLOW = '43';
    const string BG_BLUE = '44';
    const string BG_MAGENTA = '45';
    const string BG_CYAN = '46';
    const string BG_LIGHT_GREY = '47';

    private string $str;
    private ?string $backgroundColor = null;
    private string $textColor = self::TEXT_WHITE;

    public static function text(string $str): self
    {
        return new self($str);
    }

    private function __construct(string $str)
    {
        $this->str = $str;
    }

    public function background($backgroundColor): self
    {
        $this->backgroundColor  = $backgroundColor;
        return $this;
    }
    public function textColor($textColor): self
    {
        $this->textColor  = $textColor;
        return $this;
    }
    public function __toString(): string
    {
        return "\e[".$this->textColor.

            (null != $this->backgroundColor ? ';'.$this->backgroundColor : '')

            .'m'.$this->str."\e[0m";
    }
}