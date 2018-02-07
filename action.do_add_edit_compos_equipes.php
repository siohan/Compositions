<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Compositions use'))
{
	$designation.=$this->Lang('needpermission');
	$this->SetMessage("$designation");
	$this->RedirectToAdminTab('compos');
}
debug_display($params, 'parameters');
if(isset($params['cancel']) && $params['cancel'] == 'Annuler')
{
	$this->Redirect($id, 'defaultadmin', $returnid);
}
$error = 0;
if (isset($params['ref_action']) && $params['ref_action'] != '')
{
	$ref_action = $params['ref_action'];
}
else
{
	$error++;
}
if (isset($params['ref_equipe']) && $params['ref_equipe'] != '')
{
	$ref_equipe = $params['ref_equipe'];
}
else
{
	$error++;
}
		
if($error ==0)
{
	//on vire toutes les données de cette compo avant 
	$query = "DELETE FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
	$dbquery = $db->Execute($query, array($ref_action, $ref_equipe));
	
	//la requete a fonctionné ?
	
	if($dbquery)
	{
		$licence = '';
		if (isset($params['licence']) && $params['licence'] != '')
		{
			$licence = $params['licence'];
			$error++;
		}
		foreach($licence as $key=>$value)
		{
			$query2 = "INSERT INTO ".cms_db_prefix()."module_compositions_compos_equipes (ref_action, ref_equipe, licence) VALUES (?, ?, ?)";
		//	echo $query2;
			$dbresultat = $db->Execute($query2, array($ref_action, $ref_equipe,$key));
		}
	$this->SetMessage('participants ajoutés !');
	}
	else
	{
		echo "la requete de suppression est down !";
	}
		
		
}
else
{
	echo "Il y a des erreurs !";
}
		


$this->Redirect($id, 'view_compos', $returnid, array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe));

?>