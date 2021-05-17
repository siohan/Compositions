<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');

$licence = '';
$rowarray = array();

if(!isset($params['genid']) || $params['genid'] == '')
{
	$this->SetMessage("parametres manquants");
	$this->RedirectToAdminTab('groups');
}
else
{
	$genid = $params['genid'];
}

	
$db = $this->GetDb();
//on va sélectionner les licences déjà présentes en bdd pour cette cotisation et l'exclure de la liste
$query = "SELECT id, ref_action, id_cotisation, genid FROM ".cms_db_prefix()."module_cotisations_belongs WHERE genid = ?";
$dbresult = $db->Execute($query, array($genid));
$count = $dbresult->RecordCount();

if($count > 0)
{
	//on montre les cotisations de l'adhérent
	//echo "on y est !";
	$this->Redirect($id, 'view_adherent_cotis', $returnid, array("genid"=>$genid));
	
}
elseif($count == 0)
{
	$this->Redirect($id, 'assign_groups', $returnid, array("genid"=>$genid));
	
}

#
#EOF
#
?>