<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$ping = cms_utils::get_module('Ping');
$ping_ops = new ping_admin_ops;
$saison = $ping->GetPreference('saison_en_cours');
$lock = '<img src="../modules/Compositions/images/lock.png" class="systemicon" alt="Déverrouiller" title="Déverrouiller">';
$unlock = '<img src="../modules/Compositions/images/unlock.png" class="systemicon" alt="Verrouiller" title="Verrouiller">';
$relance = '<img src="../modules/Paiements/images/forward-email-16.png" class="systemicon" alt="Envoyer une relance" title="Envoyer une relance">';
$details_facture = '<img src="../modules/Paiements/images/billing.jpg" class="systemicon" alt="Détails de la facture" title="Détails de la facture">';
$smarty->assign('details_facture', $details_facture);
$smarty->assign('add_edit_compo',
		$this->CreateLink($id, 'add_edit_compo', $returnid,$contents='Ajouter une journée de championnat'));

$result= array ();
$query = "SELECT id, idepreuve, journee, ref_action, actif, statut, phase, saison FROM ".cms_db_prefix()."module_compositions_journees WHERE saison = ?";
$query.=" ORDER BY idepreuve ASC, journee ASC";
$dbresult= $db->Execute($query, array($saison));
	
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
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;
				$onerow->id= $row['id'];
				$onerow->ref_action= $row['ref_action'];
				$onerow->idepreuve= $row['idepreuve'];
				$nb = $comp_ops->nb_equipes_idepreuve($row['idepreuve'], $row['phase']);
				$nb = (int)$nb;
				//var_dump($nb);
				$onerow->equipes_concernees = $nb;//$comp_ops->nb_equipes_idepreuve($row['idepreuve']);
				$onerow->pourcentage_remplissage = $comp_ops->pourcentage_remplissage($row['ref_action']);
				$onerow->championnat= $ping_ops->nom_compet($row['idepreuve']);//$row['idepreuve'];
				$onerow->journee= $row['journee'];
				$onerow->phase= $row['phase'];
				if($actif == 1)
				{
					$onerow->actif = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'),'','','systemicon');
					if($statut == 0)
					{
						$onerow->statut = $this->CreateLink($id, 'lock', $returnid, $unlock, array("lock"=>"1","ref_action"=>$row['ref_action']), $warn_message='Cette opération va aussi modifier le brulage');//$themeObject->DisplayImage('icons/system/true.gif', $this->Lang('delete'), '', '', 'systemicon');

						//$onerow->view_reglement = $this->CreateLink($id, 'view_reglements',$returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'), array('record_id'=>$row['ref_action']));
					}
					elseif($statut == 1)
					{
						$onerow->statut = $this->CreateLink($id, 'unlock', $returnid, $lock, array("lock"=>"0","ref_action"=>$row['ref_action']));

					}
				}
				else
				{
					$onerow->actif = $this->CreateLink($id, 'actif', $returnid, $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'),'','','systemicon'), array("ref_action"=>$row['ref_action'],"actif"=>"1"),$warn_message='Vous pourrez verrouiller toutes les compos pour cette journée');
					$onerow->statut = $themeObject->DisplayImage('icons/system/stop.gif', $this->Lang('stop'),'','','systemicon');
				}
				$onerow->manage = $this->CreateLink($id, 'creer_liste', $returnid, $themeObject->DisplayImage('icons/system/groupassign.gif', $this->Lang('groupassign'),'','','systemicon'),array("idepreuve"=>$row['idepreuve']));
				$onerow->view = $this->CreateLink($id, 'view_compos', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'),array("ref_action"=>$row['ref_action'],"idepreuve"=>$row['idepreuve']) );
				$onerow->edit = $this->CreateLink($id, 'add_edit_compo', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'),array("record_id"=>$row['ref_action']) );
				$onerow->duplicate = $this->CreateLink($id, 'duplicate', $returnid, $themeObject->DisplayImage('icons/system/copy.gif', $this->Lang('copy'), '', '', 'systemicon'),array("ref_action"=>$row['ref_action']) );
				$onerow->delete = $this->CreateLink($id, 'delete', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'),array("ref_action"=>$row['ref_action']),$warn_message='Supprimer la journée et toutes ses compos ?' );
				
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