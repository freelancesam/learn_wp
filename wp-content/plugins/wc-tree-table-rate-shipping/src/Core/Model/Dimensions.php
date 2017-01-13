<?php
namespace Trs\Core\Model;


/**
 * @property-read float $length
 * @property-read float $width
 * @property-read float $height
 */
class Dimensions
{
    public function __construct($length, $width, $height)
    {
        $this->length = (float)$length;
        $this->width = (float)$width;
        $this->height = (float)$height;
    }

    public function __get($property)
    {
        return $this->{$property};
    }

    private $length;
    private $width;
    private $height;
}