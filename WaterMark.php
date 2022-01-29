<?php

class WaterMark{
    /*
    * @bg = Background
    * @pic = watermark
    * @bg = alignment of watermark
    * @bg = extention of output
    */
    public $bg;
    public $pic;
    public $align;
    public $ext;
    public $output_name;

    /*
    * setting the defaults
    */
    public function __construct($pic, $bg, $align = 'center', $ext = 'jpg', $output_name=null){
        $this->bg = $bg;
        $this->pic = $pic;
        $this->align = $align;
        $this->ext = $ext;
        $this->output_name = $output_name;          
    }
    public function watermark(){
        // set bg and watermark
        $watermark = imagecreatefrompng($this->pic);
        list($newwidth, $newheight) = getimagesize($this->pic);
        $bg = imagecreatefromjpeg($this->bg);
        list($width, $height) = getimagesize($this->bg);
        
        // create output canvas based on bg
        $out = imagecreatetruecolor($width, $height);

        //switch alignment
        switch ($this->align){
            case 'center':
                $src_x = ($width  -$newwidth ) / 2;
                $src_y = ($height  - $newheight) / 2;
            case 'right':
                $src_x = ($width  -$newwidth );
                $src_y = 0;    
            case 'left':    
                $src_x = 0;
                $src_y = 0;
            case 'top':    
                $src_x = ($width  -$newwidth ) / 2;
                $src_y = 0;
            case 'bottom':  
                $src_x = ($width  -$newwidth ) / 2;
                $src_y = ($height  - $newheight);
            default:
                $src_x = ($width  -$newwidth ) / 2;
                $src_y = ($height  - $newheight) / 2;
        }
        
        // set bg on canvas
        imagecopyresampled($out, $bg, 0, 0, 0, 0, $width, $height, $width, $height);
        // set watermark on bg
        imagecopyresampled($out, $watermark,  $src_x, $src_y, 0, 0, $newwidth, $newheight, $newwidth, $newheight);
        //create output
        if(!$this->output_name)
            $this->output_name = 'out'.microtime(true);

        imagejpeg($out, $this->output_name.'.'.$this->ext);
    }
}

$obj = new watermark('./pic.png','./bg.jpeg','left','jpg');
$obj->watermark();
