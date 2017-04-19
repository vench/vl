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
            
            $bit = $v & 1;
            $cbit = $v - $bit >> 1;
           
            for($i = 0; $i < $cbit; $i ++) {
               $dataImage[$index ++] =  $bit * 255;
               $dataImage[$index ++] =  $bit * 255;
               $dataImage[$index ++] =  $bit * 255;
               $dataImage[$index ++] =  255;
            }
        }
        
        return $dataImage;
    }    
    
}
