<?php
 
namespace app\components\image;

/**
 * Description of ImageDataGIF
 *
 * @author vench
 */
class ImageDataGIF extends ImageData {

    /**
     * 
     * @param resource $image
     */
    protected function renderImage($image) {
        header ('Content-Type: image/gif');
 
        imagegif($image);
        imagedestroy($image);
        exit(0);
    }
}
