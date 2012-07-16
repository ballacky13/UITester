<?php

/**
 * 
 * ark��֤���࣬ģ�巽��������SSO��֤�Ļ������Ӿ�
 *
 * the last known user to change this file in the repository  <$LastChangedBy: suqian $>
 * @author Su Qian <aoxue.1988.su.qian@163.com>
 * @version $Id: ArkConfig.php 1532 2011-03-01 02:09:44Z suqian $
 * @package 
 */
abstract class ArkAuth {

	const COOKIENAME = "ArkAppAuthen";

	const COOKIEAUTHEN = "Authen";

	const COOKIEGUID = "Scid";

	const COOKIECODE = "Code";

	const USERITEM = "Ark:User";

	const APPINFO = "Ark:AppInfo";

	protected $appUser = array();

	protected $apiUser = array();
		
	public function __construct() {

	}

	public function preOnAuthRequest(Context $context) {
		
		return true;
	}

	public function OnAuthRequest(Context $context) {
		if (!$this->preOnAuthRequest($context)) 
			return false;
		
		$httpcookie = NULL;
		if (isset($_COOKIE[self::COOKIENAME])) 
			$httpcookie = strtr($_COOKIE[self::COOKIENAME], '|', '=');
		
		//ע����¼�������ʶ��
		if (isset($_GET[Config::get(LOGOUTPARAM)])) {
			$logout = $_GET[Config::get(LOGOUTPARAM)];
			if ($logout && preg_match("/$logout/i", Config::get(LOGOUTVALUE))) {
				$this->processLogout();
			}
		}
		
		//����Cookie��ֱ������HttpContext������û���Ϣ���������������Ҫ�ж���ƾ���Ƿ����
		if (!empty($httpcookie)) {
			//���ڴ�����ƾ��
			$secretid = self::getCookieValue($httpcookie, self::COOKIEGUID);
			
			if (!empty($secretid)) $secretid = IdEncryptService::Decrypt($secretid);
			
			//�Խ��ܺ��secretid����Ч�Խ����ж�,������ܺ󷵻�ֵΪ�յĻ���˵����ƾ�ݱ��������룬ֱ��ת�����
			if (empty($secretid)) $this->processSecretError(1013);
			
			//�ӻ���(session)����secretID�����ȡ�û��ܳ�
			$secretkeyiv = $this->getSecretKeyIv($context, $secretid);
			//�����������Ϣ�����������������
			if (empty($secretkeyiv)) {
				$secretkeyiv = $this->requestSecretKeyIv($secretid);
				
				if ($secretkeyiv  && $secretkeyiv['ErrorCode'] == 0) {
					//����ɹ�,����Ӧ�û����е���Կ��
					$this->_setSecretKeyIv($context, $secretid, $secretkeyiv);
				} else if ($secretkeyiv && $secretkeyiv['ErrorCode'] == 1011) {
					//�ͻ������ض�������������ض���Ark���е�¼
					$this->processLogin(true);
				} else {
					//���Cookie��ͬʱ�ض��򵽴���
					$this->processSecretError($secretkeyiv == NULL ? 1015 : $secretkeyiv['ErrorCode']);
				}
			}
			//�����û���Ϣ������session
			return $this->decryptUserAddContext($context, $secretkeyiv, $httpcookie);
		} else {
			//��ƾ�ݲ����ڵ�����£�Ҫ�ж�genericCode�Ƿ����
			$genericCode = $this->getGenericCode($context);
			if (empty($genericCode)) {
				$this->processLogin(true);
			} else {
				//����CodeȡProfile����������������
				$this->processGenericCode($context, $genericCode);
			}
		}
		return null;
	
	}

	public function processLogin($isclear = false) {
		if ($isclear) 
			setcookie(COOKIENAME, "", time() - 3600);
		
		Header("Location: " . ArkRequest::processLoginUrl(Config::get(LOGOUTPARAM)));
	}

	public function processLogout() {
		setcookie(COOKIENAME, "", time() - 3600);
		Header("Location: " . ArkRequest::processLogoutUrl(Config::get(LOGOUTPARAM)));
	}

	public function processSecretError($errorcode) {
		setcookie(COOKIENAME, "", time() - 3600);
		Header("Location: " . ArkRequest::processErrorUrl($errorcode));
	}

	/**
	 * ��Ӧ�û����л����Կ��(Ӧ�û��������Session��memcached��)
	 * 
	 * @param string $secretId ��Կ��Id
	 * @param Context $context Ӧ��������
	 * @return array|NULL
	 */
	public function getSecretKeyIv(Context $context, $secretId) {
		return $context->get($secretId);
	}

	/**
	 * ����Ӧ�û�������Կ��
	 * 
	 * @param Context $context Ӧ�û��������ģ�Ӧ�û�����ָsession��cookie��memcached�ȣ�
	 * @param string $secretId ��Կ��Id
	 * @param array $secret ��Կ��
	 */
	public function _setSecretKeyIv(Context $context, $secretId, array $secret) {
		$context->insert($secretId, $secret);
	}

	/**
	 * ����Ӧ�û�������Կ��
	 * 
	 * @param Context $context Ӧ�û��������ģ�Ӧ�û�����ָsession����cookie��
	 * @param string $secretId ��Կ��Id
	 * @param string $key ��Կ��Key
	 * @param string $iv  ��Կ��Iv
	 * @param int $expitime ��Կ�����ʱ��
	 */
	public function setSecretKeyIv(Context $context, $secretId, $key, $iv, $expitime) {
		
		$secretkeyiv['SecretKey'] = $key;
		$secretkeyiv['SecretIV'] = $iv;
		$secretkeyiv['SecretExp'] = $expitime;
		self::_setSecretKeyIv($context, $secretId, $secretkeyiv);
	}

	/**
	 * ������������Կ����Ϣ
	 * @param string $secretId ��Կ��Id
	 * @return array
	 */
	public function requestSecretKeyIv($secretId,$isExpired = NULL) {
		$secretString = ArkRequest::requestUrl(ArkRequest::processSecretUrl($secretId,$isExpired), NULL);
		if (empty($secretString)) 
			return NULL;
		
		return json_decode($secretString, true);
	}

	/**
	 * Authcode or ticket
	 * 
	 * @param Context $context
	 * @return $context
	 */
	public abstract function getGenericCode(Context $context);

	/**
	 * Process authcode or ticket
	 * 
	 * @param unknown_type $context
	 * @param unknown_type $genericcode
	 */
	public abstract function processGenericCode(Context $context, $genericcode);

	/**
	 * �����û���Ϣ����ӵ�Ӧ����
	 * 
	 * @param Context $context Ӧ�û���������
	 * @param unknown_type $secretkeyiv ��Կ
	 * @param unknown_type $httpcookie ��֤Cookie
	 */
	public abstract function decryptUserAddContext(Context $context, $secretkeyiv, $httpcookie);

	/**
	 * ȡ��app�� ��֤���û���Ϣ
	 * @return array
	 */
	public function getAppUser(){
		return $this->appUser;
	}
	
	/**
	 * ȡ��api�� ��֤���û���Ϣ
	 * @return array
	 */
	public function getApiUser(){
		return $this->apiUser;
	}
	
	/**
	 * ����SSO ��֤�����е�cookie
	 * @param unknown_type $cookie
	 * @param unknown_type $value
	 * @return string
	 */
	protected function getCookieValue($cookie, $value) {
		
		$cookieArray = array();
		//cookie��&��ʶ�Ĳ�ֵ���������ȥ,���治�������������ҳ����Ӧ�ı����ı䣬�г�ͻ����
		parse_str($cookie, $cookieArray);
		//�ո���ɼӺ���Ϊ�˽��PHP�ͻ�����IIS��ʹ�õ�ʱ��,������ɵĿո�����޷���������������滻��+��Windows + IIS�ض���
		return $cookieArray[$value] !== NULL ? strtr($cookieArray[$value], ' ', '+') : false;
	}

	/**
	 * ����APP�û���Ϣ
	 * 
	 * @param Context $context
	 * @param array $user
	 * @return array
	 */
	protected function setAppLocalUserInfo(Context $context, array $user,$ifOauth = false) {
		//������������������û��������Ϣ
		$newUser = array(
			'WorkId' => $user['WorkId'], 
			'Email' => $user['Email'], 
			'DomainUser' => $user['DomainUser'], 
			'WangWang' => base64_decode($user['EWangWang']),
			'DisplayName'=>  base64_decode($user['DisplayName'])
		);
		if($ifOauth){
			$newUser['AccessToken'] = $user['AccessToken'];
			$newUser['RefreshToken'] = $user['RefreshToken'];
		}
		$context->insert(self::USERITEM, $newUser);
		$this->appUser = $newUser;
		return $newUser;
	}
	
	
	/**
	 * Enter description here ...
	 * @param Context $context
	 * @param array $user
	 * @return array
	 */
	protected function setApiLocalUserInfo(Context $context, array $user){
		//������������������û��������Ϣ
		$newUser = array(
			'WorkId' => $user['WorkId'], 
			'AppUrl' => $user['AppUrl'], 
			'DomainUser' => $user['DomainUser'], 
		);
		
		$context->insert(self::APPINFO, $newUser);
		$this->apiUser = $newUser;
		return $newUser;
	}
	
	
	
	/**
	 * ������֤���APP cookie
	 * @param array $user
	 */
	protected function setAppLocalCookieInfo(array $user,$jsonResult = ''){
		
		$Values = self::COOKIEAUTHEN.'='.EncryptService::Encrypt($jsonResult ? $jsonResult : json_encode($user), $user['SecretKey'], $user['SecretIV']);
		$Values .= '&'.self::COOKIEGUID.'='.IdEncryptService::Encrypt($user['SecretId']);
		$Values .= '&'.self::COOKIECODE.'='.CodeEncryptService::Encrypt(0);
		setcookie(self::COOKIENAME, strtr($Values, '=', '|'), NULL, '/', NULL, NULL, 1);
	}

}

