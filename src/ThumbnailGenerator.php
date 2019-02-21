<?php

namespace spayn\ImageHelpers;

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
        $this->save_path = $save_path;
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


    private function generateThumbnail($file, $save_path, $width, $height)
    {
        (new ImageHelper($file))->crop($width, $height)
            ->resize($width, $height)
            ->getImagine()
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


}
