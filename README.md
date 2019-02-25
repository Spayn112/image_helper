# Thumbnail Generator
Requirement:
jpegoptim

Usage:
```php
use spayn\ImageHelpers\ThumbnailGenerator;

$thumb_generator = new ThumbnailGenerator(
    // Save path
    __DIR__ . '/thumbs',
    // url
    '/thumbs',
    // label => resolutions
    [
        'small' => '75x75',
        'medium' => '730x410',
        'large' => '1460x820'
    ]
);

$file_path = 'path/to/image';
$save_dir = 'name/for/save/dir';

$thumb_generator->generate($file_path, $save_dir);

$url_image = 'url/to/image';
// Get 75x75 image
$thumb_generator->getFileUrl($url_image, $save_dir, 'small');

// Delete image directory
$thumb_generator->deleteThumbnailsDirectory($save_dir);
