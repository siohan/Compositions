<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
debug_display($params, 'Parameters');
//$ping = cms_utils::get_module('Ping');
$db = cmsms()->GetDb();
if(isset($params['ref_action']) && $params['ref_action'] !="")
{
	$ref_action = $params['ref_action'];
	$query = "SELECT idepreuve, journee, ref_action, actif, statut, phase, saison FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$idepreuve = $row['idepreuve'];
			$journee = $row['journee'];
			$journee = $journee+1;
			$phase = $row['phase'];
			$saison = $row['saison'];
			//on créé une nouvelle ref_action
			$neo_ref_action = $this->random_string(10);
			$comp_ops = new compositionsbis;
			//on peut dumpliquer la première compo générale
			$dup = $comp_ops->duplicate_journee($neo_ref_action, $phase, $journee, $idepreuve, $saison);
			
			if (TRUE === $dup)
			{
				$query = "SELECT ref_action, ref_equipe, licence, statut FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ?";
				$dbresult = $db->Execute($query, array($ref_action));
				if($dbresult && $dbresult->RecordCount()>0)
				{
					while($row = $dbresult->FetchRow())
					{
						$ref_equipe = $row['ref_equipe'];
						$licence = $row['licence'];
						$dup2 = $comp_ops->duplicate($neo_ref_action, $ref_action,$ref_equipe,$licence);
					}
				}
			}
			
		}
	}
	$this->RedirectToAdminTab('compos');
}
else
{
	$this->SetMessage('Vous n\'avez pas spécifier la journée à dupliquer');
	$this->RedirectToAdminTab('compos');
}
?>