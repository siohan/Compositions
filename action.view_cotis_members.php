<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');



$rowarray = array();

if(!isset($params['record_id']) || $params['record_id'] == '')
{
	$this->SetMessage("parametres manquants");
	$this->RedirectToAdminTab('groups');
}
else
{
	$record_id = $params['record_id'];
}
$adh_ops = new Asso_adherents;
$gp = new groups;
$cotis_ops = new cotisationsbis;	
$db = $this->GetDb();
//on va sélectionner les licences déjà présentes en bdd pour cette cotisation et l'exclure de la liste
$query = "SELECT be.id, be.ref_action, be.id_cotisation, be.genid FROM ".cms_db_prefix()."module_cotisations_belongs AS be, ".cms_db_prefix()."module_adherents_adherents AS adh  WHERE adh.genid = be.genid AND be.id_cotisation = ? ORDER BY adh.nom ASC";
$dbresult = $db->Execute($query, array($record_id));
if($dbresult)
{
	if($dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$onerow = new StdClass;
			$group = $cotis_ops->group_cotis($record_id);
			$onerow->group = $group;
			$onerow->genid = $row['genid'];
			$onerow->member = $gp->is_member($row['genid'], $group);
			$onerow->nom = $adh_ops->get_name($row['genid']);
			$rowarray[] = $onerow;
		}
	}
	$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
	$smarty->assign('itemcount', count($rowarray));
	$smarty->assign('items', $rowarray);
}
echo $this->ProcessTemplate('view_cotis_members.tpl');



#
#EOF
#
?>