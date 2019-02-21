<?php

namespace spayn\ImageHelpers;

use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Point\Center;
use Imagine\Imagick\Imagine;

class ImageHelper
{

    private $image;


    public function __construct($file)
    {
        $this->image = (new Imagine())->open($file);
    }


    public function getImagine()
    {
        return $this->image;
    }


    public function crop($ratio_width, $ratio_height)
    {
        $crop_ratio = $this->getRatio($ratio_width, $ratio_height);

        $size_image = $this->image->getSize();
        $width = $size_image->getWidth();
        $height = $size_image->getHeight();

        $ratio = $this->getRatio($width, $height);

        if ($ratio !== $crop_ratio) {
            if ($crop_ratio == 1) {
                if ($width > $height) {
                    $size_crop = new Box($height, $height);
                } else {
                    $size_crop = new Box($width, $width);
                }
                $this->image->crop($this->getCenterCrop($size_crop), $size_crop);
            } else {
                $size_crop = new Box($width, $width * $crop_ratio);
                $this->image->crop($this->getCenterCrop($size_crop), $size_crop);
            }
        }

        return $this;
    }


    public function resize($width, $height)
    {
        $this->image = $this->image->thumbnail(new Box($width, $height));
        return $this;
    }


    private function getCenterCrop(Box $size_crop)
    {
        $center = new Center($this->image->getSize());
        $crop_width = $size_crop->getWidth();
        $crop_height = $size_crop->getHeight();
        return new Point($center->getX() - ($crop_width / 2), $center->getY() - ($crop_height / 2));
    }


    private function getRatio($ratio_width, $ratio_height)
    {
        return round($ratio_height / $ratio_width, 2);
    }


}
