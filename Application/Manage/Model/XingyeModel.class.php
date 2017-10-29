<?php

/**
 * 兴业接口
 */

namespace Manage\Model;

class XingyeModel extends BaseModel
{
    private $_api = 'https://interface.swiftpass.cn/sppay-interface-war/gateway';//http://35api.test.swiftpass.cn/sppay-interface-war/gateway';
    private $_partner = '101510011747';//100590006610';
    private $_dataType = 'xml';
    private $_charset = 'UTF-8';
    private $_security_key = '3559fd5e45a91265ba1a5df71ca09169';//35aa6c203b7b4218713153f6d8dc39ec';

//    private $_parentMerchant = '100550006645';

    /**
     * 请求数据
     */
    public function post_data($data, $url = '')
    {
        /*
        partner
        serviceName
        dataType
        charset
        data
        dataSign
        */
        $data_xml = $data;
        unset($data_xml['serviceName']);
        $data['data'] = $this->_toXml($data_xml);
        //$data['data'] = file_get_contents('e:/Xml/normal_mch_add.xml');

        //print_r($data['data']);
        //exit;
        //unset($data['picUpload']);
        //unset($data['Merchant']);
        ('' == $url) && $url = $this->_api;

        $data['partner'] = $this->_partner;
        $data['dataType'] = $this->_dataType;
        $data['charset'] = $this->_charset;

        $data_arr =$this->_get_sign($data);
        $data['dataSign'] = $data_arr['sign'];
        //$url .= '?'. $data_arr['str'] . '&dataSign=' . $data['dataSign'];
        
        //dump($url);
        //dump($data);
        //exit;
        if(isset($data['serviceName']) && $data['serviceName'] == 'pic_upload')
        {
            $param3 = new \CURLFile();
            $param3->name = $_FILES['file']['tmp_name'];
            $param3->mine = $_FILES['file']['type'];
            $param3->postname = $_FILES['file']['name'];

            $data['param3'] = $param3;
            //$data['param3'] = $_FILES['file'];
        }
        //dump($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, "Content-Type: multipart/form-data;");
        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }

    /**
     * 兴业银行进件接口
     */
    public function jinjian($info)
    {
        //dump($info);exit;

        $outMerchantId = rand(10000000, 99999999);
        $m = D('Manage/GdDistrict');
//        $provice = $m->getProvince();
//        $city = $m->get($info['sid']);
        $data = array(
            'serviceName' => 'normal_mch_add',

            //商户基本信息
            'merchant' => array(
                'merchantName' => $info['userName'],//商户名称
                'outMerchantId' => $outMerchantId,//外商户号
                'feeType' => 'CNY',//
                'mchDealType' => 1,//商户经营类型,1:实体，2:虚拟
                'remark' => '备注:商户进件',//备注(Y)
                'chPayAuth' => $info['zz_payAuth'],//渠道授权交易(Y)【0】
                //'merchantDetail' => '商户详情',//商户详情
                //'bankAccount' => $info['zz_bankinfo'],//银行账户

                //商户详情信息
                'merchantDetail' => array(
                    'merchantShortName' => $info['zz_jc'],//'橙子科技商户简介',//商户简介
                    'industrId' => $info['incode'],//行业类别
                    'province' => $info['fid'],//'120000',//省份
                    'city' => $info['sid'],//'040100',//城市
                    'address' => $info['zz_zcdz'],//地址
                    'tel' => $info['userPhone'],//电话(Y)
                    'email' => $info['userEmail'],//邮箱
                    'legalPerson' => $info['zz_name'],//企业法人
                    'customerPhone' => $info['userPhone'],
                    'principal' => $info['zz_accountname'],//'陈龙',
                    'principalMobile' => $info['userPhone'],//负责人手机号
                    'idCode' => $info['zz_sfz'],//负责人身份证号
                    'indentityPhoto' => $info['sfzz'] . ';' . $info['sfzf'],//身份证图片
                    //'licensePhoto' => '',//营业执照
                    //'protocolPhoto' => '',//商户协议照
                    //'orgPhoto' => '',//组织机构代码照
                ),

                //银行账户信息
                'bankAccount' => array(
//                    'accountId' => 1,
                    'accountCode' => $info['zz_bankinfo'],//银行卡号
                    'bankId' => $info['zz_bank'],//'14',//开户银行
                    'accountName' => $info['zz_accountname'],//开户人
                    'accountType' => $info['zz_banktype'],//账户类型
                    'contactLine' => $info['backcode'],//'301290050471',//联行号
                    'bankName' => $info['zz_bankqc'],//'交通银行上海奉贤支行',//开户支行名称
                    'province' => $info['fid'],//'090000',//开户支行所在省
                    'city' => $info['sid'],//'090900',//开户支行所在市
                    'idCardType' => $info['zz_sftype'],//持卡人证件类型
                    'idCard' => $info['zz_sfz'],//持卡人证件号码
//                    'address' => $provice[$info['fid']].$city,//持卡人地址
                    'tel' => $info['bankPhone'],//手机号码
                ),
            ),
        );
        $rs = $this->post_data($data);
        $rs = xmlToArray($rs);

        return $rs;
    }

    private function _get_sign($data)
    {
        $return = array();

        ksort($data);

        $new_data = array();
        foreach ($data as $key => $value)
        {
            $new_data[] = "{$key}={$value}";
        }

        $data_str = implode('&', $new_data);
        $return['str'] = $data_str;

        $security_key = $this->_security_key;
        $data_str .= $security_key;

        //dump($data_str);

        $sign = md5($data_str);
        $return['sign'] = $sign;

        return $return;
    }

    private function _toXml($array)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        /**
         * <?xml version="1.0" encoding="UTF-8"?><picUpload><picType>1</picType></picUpload>
         */

        foreach ($array as $key => $value)
        {
            $xml .= "<{$key}>";
            if(is_array($value))
            {
                foreach ($value as $key2 => $value2)
                {
                    $xml .= "<{$key2}>";
                    if(is_array($value2))
                    {
                        foreach ($value2 as $key3 => $value3)
                        {
                            $xml .= "<{$key3}>";
                            $xml .= "{$value3}";
                            $xml .= "</{$key3}>";
                        }
                    }
                    else
                    {
                        $xml .= "{$value2}";
                    }
                    $xml .= "</{$key2}>";
                }
            }
            else
            {
                $xml .= "{$value}";
            }
            $xml .= "</{$key}>";
        }

        return $xml;
    }
    
    /*
     * 增加支付类型
     */
    public function addPayType($params){
        $data = array(
            'serviceName' => 'normal_mch_pay_conf_add',
            'mchPayConf'=>array(
                'merchantId' => $params['merchantId'],
                'payTypeId' => $params['payTypeId'],
                'billRate' => $params['billRate'],
            ),                                    
        );
        if(isset($params['partner'])){
            $data['MchPayConf']['partner'] = $params['partner'];           
        }
        
        $rs = $this->post_data($data);
        $rs = xmlToArray($rs);
        return $rs;
    }
}