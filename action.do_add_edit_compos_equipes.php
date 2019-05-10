<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Compositions use'))
{
	$designation.=$this->Lang('needpermission');
	$this->SetMessage("$designation");
	$this->RedirectToAdminTab('compos');
}
debug_display($params, 'parameters');
$eq_comp = new equipes_comp;
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
	$details = $eq_comp->details_equipe($ref_equipe);
	$nb_joueurs_mini = $details['nb_joueurs'];
}
else
{
	$error++;
}
		
if($error ==0)
{
	$message = '';
	//on vire toutes les données de cette compo avant 
	$query = "DELETE FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
	$dbquery = $db->Execute($query, array($ref_action, $ref_equipe));
	
	//la requete a fonctionné ?
	
	if($dbquery)
	{
		$genid = '';
		if (isset($params['genid']) && $params['genid'] != '')
		{
			$genid = $params['genid'];
			$error++;
		}
		$i = 0;
		foreach($genid as $key=>$value)
		{
			$query2 = "INSERT INTO ".cms_db_prefix()."module_compositions_compos_equipes (ref_action, ref_equipe, genid) VALUES (?, ?, ?)";
		//	echo $query2;
			$dbresultat = $db->Execute($query2, array($ref_action, $ref_equipe,$key));
			$i++;
		}
		if($i < $nb_joueurs_mini)
		{
			$message.= "Attention équipe incomplete !";
		}
		$message.= 'participants ajoutés !';
	$this->SetMessage($message);
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