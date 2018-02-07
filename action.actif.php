<?php

if(!isset($gCms)) exit;
$db =& $this->GetDb();

if(!$this->CheckPermission('Compositions use'))
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

if(isset($params['actif']) && $params['actif'] != '')
{
	$actif = $params['actif'];
}
else
{
	$error++;
}
		
if($error <1)
{
	$comp_ops = new compositionsbis;
	if($actif == '1')
	{
		$comp_ops->actif($ref_action, $actif=1);
		//var_dump($act);
		$this->SetMessage('Activé');
	}
	else
	{
		$act = $comp_ops->actif($ref_action, $actif=0);
		$this->SetMessage('Désactivé');
	}
}

$this->RedirectToAdminTab('compos');





#
#EOF
#
?>