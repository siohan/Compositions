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
if(isset($params['lock']) && $params['lock'] != '')
{
	$lock = $params['lock'];
}
else
{
	$error++;
}
$obj = "journee";
if(isset($params['ref_equipe']) && $params['ref_equipe'] != '')
{
	$ref_equipe = $params['ref_equipe'];
	$obj = "team";
}		
if($error <1)
{
	$comp_ops = new compositionsbis;

	switch($obj)
	{
		case "team":
			
			$comp_ops->lock_equipe($ref_action, $ref_equipe);
			//var_dump($act);
			$this->SetMessage('Verrous activés pour cette équipe');
			$this->Redirect($id,'view_compos',$returnid, array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe));
		break;
		
		case "journee" :
			
			$comp_ops->lock($ref_action);
			$this->SetMessage('Verrous activés');
			$this->RedirectToAdminTab('compos');
		break;
		
	}
	
	
	
	
}







#
#EOF
#
?>