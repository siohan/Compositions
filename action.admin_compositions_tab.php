<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$timbre = time();
$lock = '<img src="../modules/Compositions/images/lock.png" class="systemicon" alt="Déverrouiller" title="Déverrouiller">';
$unlock = '<img src="../modules/Compositions/images/unlock.png" class="systemicon" alt="Verrouiller" title="Verrouiller">';
$smarty->assign('add_edit_compo',
		$this->CreateLink($id, 'add_edit_compo', $returnid,$contents='Ajouter une journée'));

$result= array ();
$query = "SELECT id, idepreuve, journee, ref_action, actif, statut, date_limite FROM ".cms_db_prefix()."module_compositions_journees ";
$query.=" ORDER BY idepreuve ASC, journee ASC";
$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = 'row1';
	//$row = 'row1';
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			
			$yesimage = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('download_poule_results'),'','','systemicon');
			$noimage = $themeObject->DisplayImage('icons/system/stop.gif', $this->Lang('download_poule_results'),'','','systemicon');
			$comp_ops = new compositionsbis;
			while ($row= $dbresult->FetchRow())
      			{
				$actif = $row['actif'];
				$statut = $row['statut'];
				$date_limite = $row['date_limite'];
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->id= $row['id'];
				$onerow->ref_action= $row['ref_action'];
				$onerow->idepreuve= $row['idepreuve'];
				$nb = $comp_ops->nb_equipes_idepreuve($row['idepreuve']);
				$nb = (int)$nb;
				//var_dump($nb);
				$nb_players = $comp_ops->player_by_idepreuve($row['idepreuve']);
				$onerow->equipes_concernees = $nb;//$comp_ops->nb_equipes_idepreuve($row['idepreuve']);
				if($nb_players == 0)
				{
					$onerow->nb_players = $comp_ops->player_by_idepreuve($row['idepreuve']);
				}
				else
				{
					$onerow->nb_players = $comp_ops->player_by_idepreuve($row['idepreuve']);
				}
				
				$onerow->pourcentage_remplissage = $comp_ops->pourcentage_remplissage($row['ref_action']);
				$onerow->championnat= $comp_ops->nom_compet($row['idepreuve']);//$row['idepreuve'];
				$onerow->journee= $row['journee'];
				if($actif == 1)
				{
					$onerow->actif = $this->CreateLink($id, 'actif', $returnid, $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'),'','','systemicon'), array("ref_action"=>$row['ref_action'],"actif"=>"0"));//,$warn_message='Vous pourrez verrouiller toutes les compos pour cette journée');
					//$onerow->actif = $this->CreateLink($id, 'actif', $returnid, $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'),'','','systemicon'), array("activate"=>"0","ref_action"=>$row['ref_action']));
					if($statut == 0)
					{
						$onerow->statut = $this->CreateLink($id, 'lock', $returnid, $unlock, array("lock"=>"1","ref_action"=>$row['ref_action']));//$themeObject->DisplayImage('icons/system/true.gif', $this->Lang('delete'), '', '', 'systemicon');

						//$onerow->view_reglement = $this->CreateLink($id, 'view_reglements',$returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'), array('record_id'=>$row['ref_action']));
					}
					elseif($statut == 1)
					{
						$onerow->statut = $this->CreateLink($id, 'unlock', $returnid, $lock, array("lock"=>"0","ref_action"=>$row['ref_action']));

					}
					if($date_limite > $timbre)
					{
						$onerow->emailing = $this->CreateLink($id, 'emailing', $returnid, $contents='Mail',array('ref_action'=>$row['ref_action'], 'idepreuve'=>$row['idepreuve']));
						$onerow->sms = $this->CreateLink($id, 'relance_sms', $returnid, $contents='SMS',array('ref_action'=>$row['ref_action'], 'idepreuve'=>$row['idepreuve']));
					}
					else
					{
						$onerow->emailing = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'),'','','systemicon');
						$onerow->sms = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'),'','','systemicon');
					}
					
				}
				else
				{
					$onerow->actif = $this->CreateLink($id, 'actif', $returnid, $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'),'','','systemicon'), array("ref_action"=>$row['ref_action'],"actif"=>"1"));//,$warn_message='Vous pourrez verrouiller toutes les compos pour cette journée');
					$onerow->statut = $themeObject->DisplayImage('icons/system/stop.gif', $this->Lang('stop'),'','','systemicon');
					$onerow->emailing = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'),'','','systemicon');
					$onerow->sms = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'),'','','systemicon');
				}
			//	$onerow->manage = $this->CreateLink($id, 'creer_liste', $returnid, $themeObject->DisplayImage('icons/system/groupassign.gif', $this->Lang('groupassign'),'','','systemicon'),array("idepreuve"=>$row['idepreuve']));
				$onerow->view = $this->CreateLink($id, 'view_compos', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'),array("ref_action"=>$row['ref_action'],"idepreuve"=>$row['idepreuve']) );
				$onerow->edit = $this->CreateLink($id, 'add_edit_compo', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'),array("record_id"=>$row['ref_action']) );
				$onerow->duplicate = $this->CreateLink($id, 'duplicate', $returnid, $themeObject->DisplayImage('icons/system/copy.gif', $this->Lang('copy'), '', '', 'systemicon'),array("ref_action"=>$row['ref_action']) );
				$onerow->delete = $this->CreateLink($id, 'delete', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'),array("ref_action"=>$row['ref_action'], "obj"=>"compos"),$warn_message='Supprimer la journée et toutes ses compos ?' );
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('compositions.tpl');


#
# EOF
#
?>