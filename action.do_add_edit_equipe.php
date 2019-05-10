<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Compositions use'))
{
	$designation.=$this->Lang('needpermission');
	$this->SetMessage("$designation");
	$this->RedirectToAdminTab('compos');
}
if(isset($params['cancel']))
{
	$this->RedirectToAdminTab('equipes');
}

$error = 0;
//debug_display($params, 'Parameters');

if (isset($params['record_id']) && $params['record_id'] != '')
{
	$ref_equipe = $params['record_id'];
}
if (isset($params['libequipe']) && $params['libequipe'] != '')
{
	$libequipe = $params['libequipe'];
}
if (isset($params['friendlyname']) && $params['friendlyname'] != '')
{
	$friendlyname = $params['friendlyname'];
}
if (isset($params['capitaine']) && $params['capitaine'] != '')
{
	$capitaine = $params['capitaine'];
}
if (isset($params['nb_joueurs']) && $params['nb_joueurs'] != '')
{
	$nb_joueurs = $params['nb_joueurs'];
}
if (isset($params['idepreuve']) && $params['idepreuve'] != '')
{
	$idepreuve = $params['idepreuve'];
}
if (isset($params['liste_id']) && $params['liste_id'] != '')
{
	$liste_id = $params['liste_id'];
}
		
if($error ==0)
{
	if(isset($params['submitasnew']))
	{
		//on ajoute une nouvelle équipe
		$teams_ops = new equipes_comp;
		$add_team = $teams_ops->add_team($libequipe, $friendlyname, $idepreuve, $capitaine, $nb_joueurs, $liste_id);
	}//on vire toutes les données de cette compo avant 
	else
	{
		$query = "UPDATE  ".cms_db_prefix()."module_compositions_equipes SET libequipe = ?, friendlyname = ?, capitaine = ?, nb_joueurs = ?, idepreuve = ?, liste_id = ?  WHERE id = ?";
		$dbquery = $db->Execute($query, array($libequipe, $friendlyname, $capitaine, $nb_joueurs, $idepreuve, $liste_id, $ref_equipe));
	}
	
	//la requete a fonctionné ?
	
	if($dbquery)
	{
		$this->SetMessage('équipe modifiée !');
	}
	else
	{
		$this->SetMessage('la requete est down !');
	}
		
		
}
else
{
	$this->SetMessage('Il y a des erreurs !');
}
		


$this->RedirectToAdminTab('equipes');

?>