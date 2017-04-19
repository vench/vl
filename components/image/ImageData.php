<?php
 
namespace app\components\image;

use \app\models\ImageDb ;

/**
 * Description of ImageData
 *
 * @author vench
 */
abstract class ImageData {

    /**
     *
     * @var string 
     */
    protected   $strImage;
    
    /**
     *
     * @var int 
     */
    protected   $width;
    
    /**
     *
     * @var int 
     */
    protected   $height;


    /**
     * 
     * @param string $strImage
     * @param int $width
     * @param int $height
     */
    public function __construct($strImage, $width, $height) {
        $this->strImage = $strImage;
        $this->width = $width;
        $this->height = $height;
    }
    
    
    final public function render() {
        $image = $this->createImage($this->width, $this->height);
        
        $data = UtilityImageData::unpack($this->strImage);
        
        $color = imagecolorallocate($image, 255, 255, 255); 
        
        for($y = 0; $y <  $this->height; $y ++) {
            for($x = 0; $x <  $this->width; $x ++) {
                $index = (($this->width *$y) + $x) * 4; //$y + $x + 4; 
                
                 if($data[$index])       
                imagesetpixel($image, $x, $y, $color);        
            }
        }
        
        
        $this->renderImage($image);
    }

    

    /**
     * 
     * @param ImageDb $image
     * @return ImageData
     */
    public static function create(ImageDb $image) {
        return new static($image->data, $image->width, $image->height);
    }
    
    /**
     * 
     * @param ImageDb $image
     * @param string $type
     * @return ImageData
     * @throws \yii\base\Exception
     */
    public static function createByType(ImageDb $image, $type) {
        
        switch(strtolower($type)) {
            case 'gif':
                return ImageDataGIF::create($image);
            case 'jpeg':
                return ImageDataJPEG::create($image);
            case 'png':
                return ImageDataPNG::create($image);
        }
        
        throw  new \yii\base\Exception("Not found type {$type}");
    }
    
    
    /**
     * 
     * @param int $width
     * @param int $height
     * @return resource
     */
    protected function createImage($width, $height) {
        return imagecreatetruecolor($width, $height);
    }
    
    abstract protected function renderImage($image);
}
