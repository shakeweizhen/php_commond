<?php
  /**
   * 文件脱敏函数，用于对敏感字段进行前端显示脱敏
   * @author weizhen
   * 2016-7-31
   */
  require_once('CheckFormat.php');

  class FilterSensitive{

        private $codeStr;
        private $check;

        public function __construct(){
            $this->codeStr="*";
            $this->check=new CheckFormat();
        }
        /**
         * email脱敏方法
         * @param  [String] $emailStr [email地址]
         * @return [string]           [脱敏的email地址]
         */
        public function email($emailStr){
            if ($this->check->email($emailStr)) {
                $atPosition=strpos($emailStr,'@');
                $emailHead=substr($emailStr,0,$atPosition);
                $emailFoot=substr($emailStr,$atPosition);
                $emailHeadLen=strlen($emailHead);
                $filterStart=$emailHeadLen/2;
                $filterLen=$emailHeadLen-$filterStart;
                for ($i=0; $i <$filterLen ; $i++) { 
                  $emailHead=substr_replace($emailHead,$this->codeStr,$filterStart+$i,1);
                }
                return $emailHead.$emailFoot;
            }else{
                return '';
            }
        }

        /**
         * 手机号码脱敏函数
         * @param  [type] $phoneNumber [description]
         * @return [type]              [description]
         */
        public function phone($phoneNumber){
          if ($this->check->phone($phoneNumber)) {
               for ($i=0; $i <4 ; $i++) { 
                  $phoneNumber=substr_replace($phoneNumber,$this->codeStr,3+$i,1);
               }
              return $phoneNumber;
          }else{
              return '';
          }
        }

        /**
         * [身份证号码脱敏]
         * @param  [type] $userIdNumber [description]
         * @return [type]               [description]
         */
        public function identifyNumber($userIdNumber){
           if ($this->check->idCard($userIdNumber)) {
              $identifyNumberLen=strlen($userIdNumber);
              for ($i=0; $i <4 ; $i++) { 
                 $userIdNumber=substr_replace($userIdNumber,$this->codeStr,$identifyNumberLen-$i,1);
              }
              return $userIdNumber;
           }else{
             return '';
           }

        }


  }
?>
