<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$designation = '';
$record_id = '';
$rowarray = array();
$cotis_ops = new cotisationsbis;
	
	if(!isset($params['record_id']) || $params['record_id'] == '')
	{
		$this->SetMessage("parametres manquants");
		$this->RedirectToAdminTab('groups');
	}
	else
	{
		$record_id = $params['record_id'];
		$groupe = $cotis_ops->group_cotis($record_id);
	}
	
$db = $this->GetDb();

$query = "SELECT j.genid, CONCAT_WS(' ', j.nom, j.prenom) AS joueur FROM ".cms_db_prefix()."module_adherents_adherents AS j LEFT JOIN  ".cms_db_prefix()."module_adherents_groupes_belongs AS be ON j.genid = be.genid WHERE j.actif = 1 AND be.id_group = ? ORDER BY j.nom ASC, j.prenom ASC ";
$dbresult = $db->Execute($query,array($groupe));

	
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('assign_users.tpl'), null, null, $smarty);
	$tpl->assign('record_id', $record_id);
//	$tpl->assign('');

	
	if($dbresult && $dbresult->RecordCount()>0)
	{
		
		$i = 0;
		while($row = $dbresult->FetchRow())
		{
			$i++;
			$tpl->assign('genid_'.$i, $row['genid']);
			$tpl->assign('nom_'.$i, $row['joueur']);
			$participe = $cotis_ops->belongs_exists($row['genid'], $record_id);
			if(true == $participe)
			{
				$tpl->assign('check_'.$i, true);
			}
						
		}
		$tpl->assign('compteur', $i);
	}
	
	$tpl->display();
#
#EOF
#
?>