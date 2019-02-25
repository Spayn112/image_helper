<?php

namespace spayn\ImageHelpers;

use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Imagick\Imagine;

class ThumbnailGenerator
{

    /**
     * Path to save thumbnails
     */
    private $save_path;

    /**
     * Url to thumbnails
     */
    private $url;

    /**
     * List resolutions
     * label => resolution
     */
    private $resolutions = [
        'small' => '75x75',
        'medium' => '730x410'
    ];


    /**
     * ThumbnailGenerator constructor.
     *
     * @param string $save_path
     * @param string $url
     * @param array $resolutions
     */
    public function __construct(string $save_path, string $url, array $resolutions = [])
    {
        $this->save_path = rtrim($save_path, DIRECTORY_SEPARATOR);
        $this->url = $url;
        if ($resolutions) {
            $this->resolutions = $resolutions;
        }
    }


    /**
     * return thumbnail image url
     *
     * @param string $file_url
     * @param string $thumb_directory
     * @param string $prefix
     * @return string
     */
    public function getFileUrl(string $file_url, string $thumb_directory, string $prefix)
    {
        $ext = pathinfo($file_url)['extension'];
        return $this->url . '/' . $thumb_directory . '/' . $prefix . '.' . $ext;
    }


    /**
     * Generate thumbnails
     *
     * @param string $file
     * @param string $thumb_directory
     */
    public function generate(string $file, string $thumb_directory)
    {
        $ext = pathinfo($file)['extension'];
        $this->createDirectory($this->save_path . DIRECTORY_SEPARATOR . $thumb_directory);
        foreach ($this->resolutions as $prefix => $resolution) {
            list($width, $height) = explode('x', $resolution);
            $save_path = $this->getThumbnailPath($thumb_directory, $prefix, $ext);
            $this->generateThumbnail($file, $save_path, $width, $height);
        }
    }


    /**
     * Delete thumbnails directory
     *
     * @param string $thumb_directory
     *
     * @return boolean
     */
    public function deleteThumbnailsDirectory(string $thumb_directory)
    {
        $dir = $this->save_path . DIRECTORY_SEPARATOR . $thumb_directory;
        if (file_exists($dir)) {
            return $this->removeDirectory($dir);
        }
        return true;
    }


    /**
     * Crop, resize and save thumbnail
     *
     * @param string $file
     * @param string $save_path
     * @param integer $width
     * @param integer $height
     */
    private function generateThumbnail(string $file, string $save_path, int $width, int $height)
    {
        (new Imagine())
            ->open($file)
            ->thumbnail(new Box($width, $height), ManipulatorInterface::THUMBNAIL_OUTBOUND)
            ->save($save_path);
    }


    /**
     * Get path to thumbnail image
     *
     * @param string $thumb_directory
     * @param string $prefix
     * @param string $extension
     * @return string
     */
    private function getThumbnailPath(string $thumb_directory, string $prefix, string $extension)
    {
        $new_filename = "$prefix." . $extension;
        return $this->save_path . DIRECTORY_SEPARATOR . $thumb_directory . DIRECTORY_SEPARATOR . $new_filename;
    }


    /**
     * Creates a directory recursively
     *
     * @param string $directory
     * @return bool
     */
    private function createDirectory(string $directory)
    {
        if (file_exists($directory)) {
            return true;
        }
        return mkdir($directory, 0777, true);
    }


    /**
     * Deletes a directory recursively
     *
     * @param $dir
     * @return bool
     */
    private function removeDirectory(string $dir)
    {
        if ($objs = glob($dir . '/*')) {
            foreach($objs as $obj) {
                is_dir($obj) ? $this->removeDirectory($obj) : unlink($obj);
            }
        }
        return rmdir($dir);
    }


}
