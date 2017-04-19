<?php

 

namespace app\components\image;

/**
 * Description of ImageDataJPEG
 *
 * @author vench
 */
class ImageDataJPEG extends ImageData {

    /**
     * 
     * @param resource $image
     */
    protected function renderImage($image) {
        header ('Content-Type: image/jpg');
 
        imagejpeg($image);
        imagedestroy($image);
        exit(0);
    }
}
