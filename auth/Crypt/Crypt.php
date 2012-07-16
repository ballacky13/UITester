<?php
require  'AES.php';
/**
 * ʹ�û���Rjindael��AES�ԳƼ����㷨
 *
 * the last known user to change this file in the repository  <$LastChangedBy: suqian $>
 * @author Su Qian <aoxue.1988.su.qian@163.com>
 * @version $Id: Crypt.php 1532 2011-03-01 02:09:44Z suqian $
 * @package 
 */
class EncryptService{
	
	private function __construct(){
		
	}
	
	public static function Encrypt($source, $key, $iv){
		$mobjCryptoService = new Crypt_AES();
		$mobjCryptoService->setKey(base64_decode($key));
		$mobjCryptoService->setIV(base64_decode($iv));
		$result = $mobjCryptoService->encrypt($source);
		//׷��һ�η�ת
		$result = strrev($result);
		//base64���룬�������ڽ���ʱУ��������
		return base64_encode($result);
	}
	//ʧ��ʱ����null
	public static function Decrypt($source, $key, $iv){
		$mobjCryptoService = new Crypt_AES();
		//base64���룬У��������
		$source = base64_decode($source);
		if($source !== false)
		{
			//׷��һ�η�ת
			$source = strrev($source);
			$mobjCryptoService->setKey(base64_decode($key));
			$mobjCryptoService->setIV(base64_decode($iv));
			return $mobjCryptoService->decrypt($source);
		}
		else return NULL;
	}
}

/**
 * Enter description here ...
 *
 * the last known user to change this file in the repository  <$LastChangedBy: suqian $>
 * @author Su Qian <aoxue.1988.su.qian@163.com>
 * @version Crypt.php 1532 2011-03-01 02:09:44Z suqian $
 * @package 
 */
final class IdEncryptService{
	
	private function __construct(){
		
	}
	
	public static function Encrypt($source){
		$bytIn = strrev($source);
		return base64_encode($bytIn);
	}
	//����ʧ�ܷ���NULL
	static function Decrypt($source){
		$bytIn = base64_decode($source);
		if($bytIn === false) return NULL;
		else return strrev($bytIn);
	}
}

/**
 * Enter description here ...
 *
 * the last known user to change this file in the repository  <$LastChangedBy: suqian $>
 * @author Su Qian <aoxue.1988.su.qian@163.com>
 * @version Crypt.php 1532 2011-03-01 02:09:44Z suqian $
 * @package 
 */
final class CodeEncryptService{
	
	private function __construct(){
		
	}
	public static function Encrypt($source){
		$bytIn = $source;
		$chars = base64_encode($bytIn);
		return strrev($chars);
	}
	//����ʧ�ܷ���NULL
	public static function Decrypt($source){
		$chars = strrev($source);
		$chars = base64_decode($chars);
		if($chars === false) return NULL;
		else return $chars;
	}
}