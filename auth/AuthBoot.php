<?php
require_once 'Common.php';

$context = ArkFactory::getContext();
//��ʼ��֤����
$ArkAuth = ArkFactory::getArkAuth();

$ArkAuth->OnAuthRequest($context);

