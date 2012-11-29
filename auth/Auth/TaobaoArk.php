<?php

require_once ('ArkAuth.php');

/**
 * �Ա��ڲ�SSO
 *
 * the last known user to change this file in the repository  <$LastChangedBy: suqian $>
 * @author Su Qian <aoxue.1988.su.qian@163.com>
 * @version $Id: TaobaoArk.php 1532 2011-03-01 02:09:44Z suqian $
 * @package 
 */
class TaobaoArk extends ArkAuth {

	const GENERICCODEKEY = 'ticket';
	
	/* (non-PHPdoc)
	 * @see ArkAuth::getGenericCode()
	 */
	public function getGenericCode(Context $context) {
		return isset($_GET[self::GENERICCODEKEY]) ? $_GET[self::GENERICCODEKEY] : NULL;
	}

	/* 
	 * @see ArkAuth::processGenericCode()
	 */
	public function processGenericCode(Context $context, $genericcode) {
		
		//�����û���ݵ�Profile
		$jsonResult = ArkRequest::requestUrl(ArkRequest::processProfileUrl($genericcode), NULL);
		$user = NULL;
		if (!empty($jsonResult))
			$user = json_decode($jsonResult, true);//����һ�����飬����ʱ����NULL

		//json������ȷ���ҷ�����������
		if (isset($user['ErrorCode']) && $user['ErrorCode'] == 0){
			//������Կ��Ļ�����Ϣ
			$this->setSecretKeyIv($context,$user['SecretId'], $user['SecretKey'], $user['SecretIV'], $user['SecretExp']);
			//������������������û��������Ϣ
			$this->setAppLocalUserInfo($context, $user);
			//д��Client��Cookie��ȥ
			$this->setAppLocalCookieInfo($user);
		}else{
			//ʹ�����Ѿ�ʹ�ù���Ticket����������UserProfile��ʱ�򷵻�1001����,���ʱ����Ҫ��������ArkServer�����Ticket
			//������ϲ���ʼ���޷��õ���ݣ����ܻ������ѭ�����ظ�����
			$this->processLogin();
		}
	}

	/* 
	 * @see ArkAuth::decryptUserAddContext()
	 */
	public function decryptUserAddContext(Context $context, $secretkeyiv, $httpcookie) {
		$encryptjson = $this->getCookieValue($httpcookie, self::COOKIEAUTHEN);
		
		if(empty($secretkeyiv['SecretKey'])) 
			$secretkeyiv['SecretKey'] = null;
		if(empty($secretkeyiv['SecretIV'])) 
			$secretkeyiv['SecretIV'] = null;
			
		$decryptjson = EncryptService::Decrypt($encryptjson, $secretkeyiv['SecretKey'], $secretkeyiv['SecretIV']);

		//���JSON����Ϊ��ʱ������û���Ϣ��Ӧ����ȥ,����ֱ�Ӳ����κβ���
		if (!empty($decryptjson)){
			$user = json_decode($decryptjson, true);
			$this->setAppLocalUserInfo($context, $user);
		}else{
			//����������ʱ��ֱ������û���Cookie��Ϣ
			self::processSecretError(1100);
		}
		return $decryptjson;
	}

}
