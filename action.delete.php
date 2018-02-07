<?php

if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
    	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
   
}

if( isset($params['cancel']) )
{
    	$this->RedirectToAdminTab('compos');
    	return;
}

debug_display($params, 'Parameters');
$db =& $this->GetDb();
$error = 0;
$comp_ops = new compositionsbis;
if(isset($params['ref_action']) && $params['ref_action'] != '')
{
	$ref_action = $params['ref_action'];
}
else
{
	$error++;
}
if(isset($params['ref_equipe']) && $params['ref_equipe'] != '')
{
	$ref_equipe = $params['ref_equipe'];
	$obj = "equipe";
}
else
{
	$obj = "compos";
}
if($error == 0)
{
	switch($obj)
	{
		case "equipe" :
			$del = $comp_ops->delete_compo_equipe($ref_action, $ref_equipe);
			$this->SetMessage('Composition équipe supprimée !');
			$this->Redirect($id, 'view_compos', $returnid, array("ref_action"=>$ref_action));
		break;
		
		case "compos" :
			$del = $comp_ops->delete_compo($ref_action);
			$delete_journee = $comp_ops->delete_journee($ref_action);
			$this->SetMessage('Compositions supprimées !');
			$this->Redirect($id, 'defaultadmin', $returnid);
			
		break;
	}
}

?>