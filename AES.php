<?php
/**
 * AES128加解密类
 * @author weizhen
 *
 */
require_once('CheckFormat.php');

class AES{

    //密钥
    private $secrectKey;

    public function __construct(){
        if ($this->dbaeskey) {
          $this->secrectKey=$this->dbaeskey;
        }else{
          $this->secrectKey = 'alefxhgSDYIOktyqwr';
        }
    }

    /**
    * This was AES-128 / CBC / NoPadding encrypted.
    * @param string $plaintext
    */
    public function encrypt($plaintext){
        $plaintext = trim($plaintext);
        if ($plaintext == '') {
          return '';
        }
        if(!extension_loaded('mcrypt')) {
          throw new CException(Yii::t('yii','AesEncrypt requires PHP mcrypt extension to be loaded in order to use data encryption feature.'));
        }
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
        $this->secrectKey=self::substr($this->secrectKey===null ? Yii::app()->params['encryptKey'] : $this->secrectKey, 0, mcrypt_enc_get_key_size($module));
        /* Create the IV and determine the keysize length, use MCRYPT_RAND
        * on Windows instead */
        $iv = substr(md5($this->secrectKey),0,mcrypt_enc_get_iv_size($module));
         /* Intialize encryption */
        mcrypt_generic_init($module, $this->secrectKey, $iv);
        /* Encrypt data */
        $encrypted = mcrypt_generic($module, $plaintext);
        /* Terminate encryption handler */
        mcrypt_generic_deinit($module);
        mcrypt_module_close($module);
        return base64_encode($encrypted);
    }
          /**      * This was AES-128 / CBC / NoPadding decrypted.
          * @param string $encrypted     base64_encode encrypted string
          * @throws CException
          * @return string
          */
    public function decrypt($encrypted) {
       if ($encrypted == ''){
         return '';
       }
       if(!extension_loaded('mcrypt')) {
         throw new CException(Yii::t('yii','AesDecrypt requires PHP mcrypt extension to be loaded in order to use data encryption feature.'));
       }
       $ciphertext_dec = base64_decode($encrypted);
       $module = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
       $this->secrectKey=self::substr($this->secrectKey===null ? Yii::app()->params['encryptKey'] : $this->secrectKey, 0, mcrypt_enc_get_key_size($module));
       $iv = substr(md5($this->secrectKey),0,mcrypt_enc_get_iv_size($module));
       /* Initialize encryption module for decryption */
       mcrypt_generic_init($module, $this->secrectKey, $iv);
       /* Decrypt encrypted string */
       $decrypted = mdecrypt_generic($module, $ciphertext_dec);
       /* Terminate decryption handle and close module */
       mcrypt_generic_deinit($module);
       mcrypt_module_close($module);
       return rtrim($decrypted,"\0");
    }

    /**
    * Returns the length of the given string.
    * If available uses the multibyte string function mb_strlen.
    * @param string $string the string being measured for length
    * @return integer the length of the string
    */
    private function strlen($string) {
      return extension_loaded('mbstring') ? mb_strlen($string,'8bit') : strlen($string);
    }

    /**
    * Returns the portion of string specified by the start and length parameters.
    * If available uses the multibyte string function mb_substr
    * @param string $string the input string. Must be one character or longer.
    * @param integer $start the starting position
    * @param integer $length the desired portion length
    * @return string the extracted part of string, or FALSE on failure or an empty string.
    */
    private function substr($string,$start,$length){
      return extension_loaded('mbstring') ? mb_substr($string,$start,$length,'8bit') : substr($string,$start,$length);
    }

 }

?>
