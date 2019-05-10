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
	$this->RedirectToAdminTab('absences');
}

$error = 0;
debug_display($params, 'Parameters');
if (isset($params['record_id']) && $params['record_id'] != '')
{
	$record_id = $params['record_id'];
}

if (isset($params['licence']) && $params['licence'] != '')
{
	$licence = $params['licence'];
}
if (isset($params['date_debut']) && $params['date_debut'] != '')
{
	$date_debut = $params['date_debut'];
}
if (isset($params['date_fin']) && $params['date_fin'] != '')
{
	$date_fin = $params['date_fin'];
}
else
{
	$date_fin = $date_debut;
}
if (isset($params['motif']) && $params['motif'] != '')
{
	$motif = $params['motif'];
}
if($error ==0)
{
	
	if(isset($params['submitasnew']))
	{
		$query = "INSERT INTO  ".cms_db_prefix()."module_compositions_absences (licence, date_debut, date_fin, motif) VALUES (?, ?, ?, ?)";
		$dbquery = $db->Execute($query, array($licence, $date_debut, $date_fin, $motif));
		
	}
	else
	{
		$query = "UPDATE ".cms_db_prefix()."module_compositions_absences SET licence = ?, date_debut = ?, date_fin = ?, motif = ? WHERE id = ?";
		$dbquery = $db->Execute($query, array($licence, $date_debut, $date_fin, $motif, $record_id));
	}
	
	//la requete a fonctionné ?
	
	if($dbquery)
	{
		$this->SetMessage('absence ajoutée ou modifiée !');
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