<?php

include_once("digest.php");

class Encrypt
{
    private $original;
    private $encrypted;
    private $skey = UNIQUE_ENCRYPTION_TOKEN;

    public  function encode($value){ 
        if(!$value){return false;}
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->skey, $text, MCRYPT_MODE_ECB, $iv);
        return trim($this->safe_b64encode($crypttext)); 
    }
    
    public  function safe_b64encode($string) {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }

    public function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
    
    public function __construct($data, $level = 1, $printCLI = false)
    {
        $this->original = $data;
        $this->encrypted = $this->encode($this->_encrypt($data));
        if($level > 1)
            for($i = 1; $i < $level; $i++)
                $this->encrypted = $this->encode($this->_encrypt($this->encrypted));
        if($printCLI == true)
            echo $this->encrypted."\n";
    }
    
    private function _encrypt($data, $level)
    {
        $encrypted = "";
        $base = array();
        $key = array();
        $length = strlen($data);
        for($i = 0; $i < $length; $i++) {
            $base[] = base64_encode(chr(strlen(ord($data[$i]))).ord($data[$i]));    
        }
        $baseLength = count($base);
        $baseRand = rand(5, $baseLength);    
        
        $encrypted = $baseRand."-".implode("-",$base);
        
        return $encrypted;
    }
    
    public function getEncrypted()
    {
        return $this->encrypted;
    }
}

$encrypt = new Encrypt($_SERVER['argv'][1], (int) $_SERVER['argv'][2], (bool) $_SERVER['argv'][3]);

?>