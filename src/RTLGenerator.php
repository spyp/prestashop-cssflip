<?php
/**
 * @author Mahdi Shad ( ramtin2025[at]yahoo[dot]com )
 * @copyright Copyright iPresta.IR
 * @link iPresta.IR
 * 
 * @version 1.0.0
 *
 */
use \CSSJanus;
class rtlGenerator {
    public static $definitions = array(
        'type' => 'css' // OR 'scss'
    );
    public static function generate($directory){
        //$all_files = self::find_all_files($directory);
        $all_files = Tools::scandir($directory, self::$definitions['type']);
        foreach ($all_files as $file)
            if(pathinfo($file)['extension'] == 'css'
                    && substr(pathinfo($file)['filename'], -4) != '_rtl'){
                $file_content = file_get_contents($file);
                self::make_rtl($file_content, $file);
            }
    }
    public static function make_rtl($content, $file) {
        //make rtl current file
        $css_base = $content;
        $rtl_content = CSSJanus::transform($css_base);
        $path = pathinfo($file);
        $rtl_file = $path['dirname'].'/'.$path['filename'].'_rtl.css';
        if (file_exists($rtl_file))
            unlink($rtl_file);
        file_put_contents($rtl_file, $rtl_content);
        @chmod($rtl_file, 0644);
    }
    public static function find_all_files($dir, $ext = null) 
    {
        if (substr($dir, -1) == '/' || substr($dir, -1) == '\\')
            $dir = substr($dir,0,-1);
        $ext = ($ext) ? $ext : self::$definitions['type'];
        $root = scandir($dir);
        $result = array();
        foreach($root as $value) 
        { 
            if($value === '.' || $value === '..') {continue;} 
            if(is_file("$dir/$value")) {
                if (substr($value,-3) == $ext)
                    $result[]="$dir/$value";
                continue;
            } 
            foreach(slef::find_all_files("$dir/$value", $ext) as $value) 
                $result[]=$value;
        }
        return $result; 
    } 
}
