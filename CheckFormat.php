<?php
  /**
   * 校验字段格式的函数
   * @author weizhen
   * 2016-7-31
   */
  class CheckFormat{

      private $emailPattern;
      private $phonePattern;
      private $idCardPattern;

      public function __construct(){
          $this->emailPattern ="/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/";
          $this->phonePattern="/^1[34578]{1}\d{9}$/";
          $this->idCardPattern="/^\d{17}(\d|x|X)$/";
      }

      /**
       * 校验email
       * @param  [type] $emailAddress [description]
       * @return [type]               [description]
       */
      public function email($emailAddress){
           $emailAddress=trim($emailAddress);
           if (preg_match($this->emailPattern, $emailAddress)){
              return true;
           }else{
              return false;
           }
      }

      /**
       * 校验手机号码
       * @param  [type] $phoneNumber [description]
       * @return [type]              [description]
       */
      public function phone($phoneNumber){
        $phoneNumber=trim($phoneNumber);
        if (preg_match($this->phonePattern, $phoneNumber)){
           return true;
        }else{
           return false;
        }
      }

      /**
       * 校验身份证号码
       * @param  [type] $userIdNumber [description]
       * @return [type]               [description]
       */
      public function idCard($userIdNumber){
        $userIdNumber=trim($userIdNumber);
        //长度验证
        if (!preg_match($this->idCardPattern, $userIdNumber)) {
          return false;
        }
        //地区验证
        $City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽 宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>" 安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>" 湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>" 贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>" 宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
        if(!array_key_exists(intval(substr($userIdNumber,0,2)),$City)){
          return false;
        }
        //生日校验
        $birthday = substr($userIdNumber,6,4).'-'.substr($userIdNumber,10,2).'-'.substr($userIdNumber,12,2);
        $d = new DateTime($birthday);
        $dd = $d->format('Y-m-d');
        if($birthday != $dd){
          return false;
        }
        //身份证编码规范验证
        $idcard_base = substr($userIdNumber,0,17);
        if(strtoupper(substr($userIdNumber,17,1)) != $this->getVerifyBit($idcard_base)){
            return false;
        }
            return true;
      }
     
      // 计算身份证校验码，根据国家标准GB 11643-1999
      private function getVerifyBit($idcard_base){
        if(strlen($idcard_base) != 17){
          return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++){
          $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
      }


}
?>
