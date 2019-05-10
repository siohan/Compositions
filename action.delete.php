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
//	$obj = "equipe";
}
if(isset($params['obj']) && $params['obj'] != '')
{
	$obj = $params['obj'];
}
if($error == 0 || isset($params['record_id']))
{
	switch($obj)
	{
		//supprime une seule compos d'une équipe donnée
		case "equipe" :
			$del = $comp_ops->delete_compo_equipe($ref_action, $ref_equipe);
			$this->SetMessage('Composition équipe supprimée !');
			$this->Redirect($id, 'view_compos', $returnid, array("ref_action"=>$ref_action));
		break;
		//supprime toutes les compos d'une ref_action
		case "compos" :
			$del = $comp_ops->delete_compo($ref_action);
			$delete_journee = $comp_ops->delete_journee($ref_action);
			$this->SetMessage('Compositions supprimées !');
			$this->Redirect($id, 'defaultadmin', $returnid);
			
		break;
		//supprime une équipe de la base de données
		case "delete_eq" : 
		
			if(isset($params['record_id']) && $params['record_id'] !='')
			{
				
				$record_id = $params['record_id'];
				$del = $comp_ops->delete_eq($record_id);
				$this->SetMessage('Equipe supprimée !');
			}
			
			
			$this->Redirect($id, 'defaultadmin', $returnid);
		break;
		case "compos_joueur" : 
			if(isset($params['record_id']) && $params['record_id'] !='')
			{
				
				$record_id = $params['record_id'];
				$del = $comp_ops->delete_joueur($record_id, $ref_action);
				$this->SetMessage('joueur dispo !');
			}
			
			
			$this->Redirect($id, 'add_edit_compos_equipe', $returnid, array("ref_action"=>$params['ref_action'], "ref_equipe"=>$params['ref_equipe']));
		break;
	}
}

?>