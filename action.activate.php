<?php

if(!isset($gCms)) exit;
$db =& $this->GetDb();

if(!$this->CheckPermission('Compositions lock'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
$error = 0; //on instancie un compteur d'erreurs
if(isset($params['ref_action']) && $params['ref_action'] != '')
{
	$ref_action = $params['ref_action'];
}
else
{
	$error++;
}
if(isset($params['activate']) && $params['activate'] != '')
{
	$activate = $params['activate'];
}
else
{
	$error++;
}
	
if($error <1)
{
	$comp_ops = new compositionsbis;
	$comp_ops->actif($ref_action,$activate);	
}







#
#EOF
#
?>