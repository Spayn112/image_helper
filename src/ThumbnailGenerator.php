<?php

namespace spayn\ImageHelpers;

use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Imagick\Imagine;

class ThumbnailGenerator
{

    public $save_path;
    public $url;

    public $resolutions = [
        'small' => '75x75',
        'medium' => '730x410'
    ];


    public function __construct($save_path, $url, array $resolutions = [])
    {
        $this->save_path = rtrim($save_path, DIRECTORY_SEPARATOR);
        $this->url = $url;
        if ($resolutions) {
            $this->resolutions = $resolutions;
        }
    }


    public function getFileUrl($file_url, $thumb_directory, $prefix)
    {
        $ext = pathinfo($file_url)['extension'];
        return $this->url . '/' . $thumb_directory . '/' . $prefix . '.' . $ext;
    }


    public function generate($file, $thumb_directory)
    {
        $ext = pathinfo($file)['extension'];
        $this->createDirectory($this->save_path . '/' . $thumb_directory);
        foreach ($this->resolutions as $prefix => $resolution) {
            list($width, $height) = explode('x', $resolution);
            $save_path = $this->getThumbnailPath($thumb_directory, $prefix, $ext);
            $this->generateThumbnail($file, $save_path, $width, $height);
        }
    }


    public function deleteThumbnailsDirectory($thumb_directory)
    {
        $dir = $this->save_path . DIRECTORY_SEPARATOR . $thumb_directory;
        if (file_exists($dir)) {
            return $this->removeDirectory($dir);
        }
        return true;
    }


    private function generateThumbnail($file, $save_path, $width, $height)
    {
        (new Imagine())
            ->open($file)
            ->thumbnail(new Box($width, $height), ManipulatorInterface::THUMBNAIL_OUTBOUND)
            ->save($save_path);
    }


    private function getThumbnailPath($thumb_directory, $prefix, $extension)
    {
        $new_filename = "$prefix." . $extension;
        return $this->save_path . DIRECTORY_SEPARATOR . $thumb_directory . DIRECTORY_SEPARATOR . $new_filename;
    }


    private function createDirectory($directory)
    {
        if (file_exists($directory)) {
            return true;
        }
        return mkdir($directory, 0770, true);
    }


    private function removeDirectory($dir)
    {
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        return rmdir($dir);
    }


}
