<?php
require_once ('ArkAuth.php');

/**
 * OAuth 2.0 SSO
 *
 * the last known user to change this file in the repository  <$LastChangedBy: suqian $>
 * @author Su Qian <aoxue.1988.su.qian@163.com>
 * @version $Id: OAuthArk.php 1532 2011-03-01 02:09:44Z suqian $
 * @package 
 */
class OauthArk extends ArkAuth {
	
	const GENERICCODEKEY = 'authcode';
	
	const ACCESSTOKEN = 'accesstoken';
	
	public function preOnAuthRequest(Context $context) {
		$appcode = Config::get(APPCODE);
        $version = $this->getClientVersion();
        $clientType = strtolower(Config::get(CLIENTTYPE));
        $accessToken = $this->getAccessToken();
        if($clientType == 'api'){
        	 $jsonResult = '';
             $accessResult =$this->validateAccessToken($accessToken, $appcode, $clientType, $version, $jsonResult);
             if ($accessResult != null)
                    $this->setApiLocalUserInfo($context, $accessResult);
             return false;
        }
		return true;
	}
	
	/* 
	 * @see ArkAuth::getGenericCode()
	 */
	public function getGenericCode(Context $context) {
		return isset($_GET[self::GENERICCODEKEY]) ? $_GET[self::GENERICCODEKEY] : NULL;
	}

	/* 
	 * @see ArkAuth::processGenericCode()
	 */
	public function processGenericCode(Context $context, $genericcode) {
		
		// ����authcode��ȡaccesstoken����д��cookie��
        $appcode = Config::get(APPCODE);
        $version = $this->getClientVersion();
        $clientType = Config::get(CLIENTTYPE);
        $jsonResult = ArkRequest::requestUrl(ArkRequest::processAccessTokenUrl($genericcode, $appcode, $version), null);

        $accessTokenValue = null;
        if ($jsonResult)
           $accessTokenValue = json_decode($jsonResult,true);
            

        if ($accessTokenValue) {
           if ($accessTokenValue['IsSuccess']){
              //��֤token
              $accessResultJsonResult = '';
              $accessResult = $this->validateAccessToken($accessTokenValue['AccessToken'], $appcode, $clientType, $version, $accessResultJsonResult);
              if ($accessResult){
                  //������Կ��Ļ�����Ϣ
				  $this->setSecretKeyIv($context,$accessResult['SecretId'], $accessResult['SecretKey'], $accessResult['SecretIV'], $accessResult['SecretExp']);
				  //������������������û��������Ϣ
                  $this->setAppLocalUserInfo($context, $accessResult,true);
                  //д��Client��Cookie��ȥ
				  $this->setAppLocalCookieInfo($accessResult, $accessResultJsonResult);
				  
				   $contextUrl = preg_replace('/[&*|\?*]authcode=[^&]*/i', '', ArkRequest::getContextUrl());
				  //$contextUrl = preg_replace("/&","/?",$contextUrl);
                  Header("Location: " . $contextUrl);
              }
           }else{
               Header("Location: " . ArkRequest::processErrorUrl($accessTokenValue['ErrorCode']));
           }
       }else{
          $this->processLogin(null);
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
			if(!isset($user['AccessToken']) || empty($user['AccessToken']))
				$this->processLogin(true);
			else {
				$this->setAppLocalUserInfo($context, $user,true);
				if(strtolower(Config::get(SAFEVERSION)) == 'high'){
					$appcode = Config::get(APPCODE);
					$clientType = Config::get(CLIENTTYPE);
					$clientVersion = $this->getClientVersion();
					$newjsonresult = '';
					$newResult = $this->validateAccessToken($user['AccessToken'], $appcode, $clientType, $clientVersion, $newjsonresult);
					if($newResult){
						
						  $this->clear($context, $newResult['SecretKey']);
						  //������Կ��Ļ�����Ϣ
				 		  $this->setSecretKeyIv($context,$newResult['SecretId'], $newResult['SecretKey'], $newResult['SecretIV'], $newResult['SecretExp']);
				 		  //������������������û��������Ϣ
                 		  $this->setAppLocalUserInfo($context, $newResult,true);
                  		  //д��Client��Cookie��ȥ
				  		  $this->setAppLocalCookieInfo($newResult, $newjsonresult);
					}else{
						$this->processLogin(true);
					}
				}
			}
		}else{
			//����������ʱ��ֱ������û���Cookie��Ϣ
			$this->processSecretError(1100);
		}
		return $decryptjson;
		
	}
	
	/* 
	 * @see ArkAuth::processLogin()
	 */
	public function processLogin($isclear = false) {
		if ($isclear) 
			setcookie(COOKIENAME, "", time() - 3600);
		
		Header("Location: " . ArkRequest::processLoginUrl(Config::get(LOGOUTPARAM),$this->getClientVersion()));
	}
	
	/* 
	 * @see ArkAuth::processLogout()
	 */
	public function processLogout() {
		setcookie(COOKIENAME, "", time() - 3600);
		Header("Location: " . ArkRequest::processLogoutUrl(Config::get(LOGOUTPARAM),$this->getClientVersion()));
	}
	
	/**
	 * ȡ�÷�������
	 * 
	 * @return string
	 */
	private function getAccessToken(){
		return isset($_GET[self::ACCESSTOKEN]) ? $_GET[self::ACCESSTOKEN] : NULL;
	}
	
	/**
	 * ��֤�������Ƶ���Ч��
	 * 
	 * @param string $accessToken
	 * @param string $appCode
	 * @param string $arkClientType
	 * @param string $clientVersion
	 * @param string $jsonResult
	 * @return array
	 */
	private function validateAccessToken($accessToken, $appCode, $arkClientType, $clientVersion, &$jsonResult){

		$jsonResult = '';
        if (empty($appCode)){
            Header("Location: " . ArkRequest::processErrorUrl(1300));
            return null;
        }

        if (empty($accessToken)){
            Header("Location: " . ArkRequest::processErrorUrl(1301));
            return null;
        }

        //ȥ�������֤AccessToken, ����AccessResult
        $jsonResult = ArkRequest::requestUrl(ArkRequest::processValidateAccessTokenUrl($accessToken, $appCode, strtolower($arkClientType), $clientVersion),null);
        $accessResult = null;
        if ($jsonResult)
           $accessResult = json_decode($jsonResult,true);

        if (empty($accessResult)){
           Header("Location: " . ArkRequest::processErrorUrl(1302));
           return null;
        }

        if (!$accessResult['IsAccess']){
           Header("Location: " . ArkRequest::processErrorUrl($accessResult['ErrorCode']));
           return null;
        }
            
        return $accessResult;
    }
    
	/**
	 * ȡ�ÿͻ��˰汾��
	 * 
	 * @return string
	 */
	private function getClientVersion(){
		$clientVersion = Config::get(CLIENTVERSION);
		if(strtolower($clientVersion) == 'oauth')
			return "1.0";
		return '';
	}
	
	private function clear(Context $context,$sercretKey){
		$allCache = $context->getAllCache();
		foreach($allCache as $key=>$value){
			if(strlen($key) == 36 && is_array($value)){
				foreach($value as $_key =>$_value){
					if($_key == 'SecretKey' && $_value != $sercretKey)
						$context->delete($key);
				}
			}
			
		}
	}

}
