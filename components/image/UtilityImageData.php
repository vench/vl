<?php

 

namespace app\components\image;

/**
 * Description of UtilityImageData
 *
 * @author vench
 */
class UtilityImageData {
 
    
    /**
     * 
     * @param string $data
     * @return $dataImage
     */
   public static function unpack($data) {
        
        $dataImage = []; 
        $spl = explode('.', $data);
        $index = 0;
        foreach ($spl as $v) {
            
            list($color, $size) = explode('-', $v); 
           
            for($i = 0; $i < $size; $i ++) {
               $dataImage[$index ++] =  $color; 
            }
        }
        
        return $dataImage;
    }    
    
}
