<?php
 

namespace app\components\image;

/**
 * Description of ImageDataPNG
 *
 * @author vench
 */
class ImageDataPNG extends ImageData {
 

    /**
     * 
     * @param resource $image
     */
    protected function renderImage($image) {
        header ('Content-Type: image/png');
 
        imagepng($image);
        imagedestroy($image);
        exit(0);
    }

}
