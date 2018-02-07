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
debug_display($params, 'Parameters');
if (isset($params['record_id']) && $params['record_id'] != '')
{
	$ref_equipe = $params['record_id'];
}
else
{
	$error++;
}
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
if (isset($params['nb_joueurs']) && $params['nb_joueurs'] != '')
{
	$nb_joueurs = $params['nb_joueurs'];
}
if (isset($params['clt_mini']) && $params['clt_mini'] != '')
{
	$clt_mini = $params['clt_mini'];
}
if (isset($params['points_maxi']) && $params['points_maxi'] != '')
{
	$points_maxi = $params['points_maxi'];
}		
if($error ==0)
{
	//on vire toutes les données de cette compo avant 
	$query = "UPDATE  ".cms_db_prefix()."module_compositions_equipes SET libequipe = ?, friendlyname = ?, nb_joueurs = ?, clt_mini = ?, points_maxi = ?  WHERE ref_equipe = ?";
	$dbquery = $db->Execute($query, array($libequipe, $friendlyname, $nb_joueurs, $clt_mini, $points_maxi, $ref_equipe));
	
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