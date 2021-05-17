<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');


global $themeObject;
$shopping = '<img src="../modules/Cotisations/images/paiement.png" class="systemicon" alt="Réglez" title="Réglez">';
$smarty->assign('shopping', $shopping);
$false = $themeObject->DisplayImage('icons/extra/false.gif', $this->Lang('false'), '', '', 'systemicon');
$smarty->assign('false', $false);

$use_paiements = $this->GetPreference('use_paiements');

$query = "SELECT nom,id FROM ".cms_db_prefix()."module_cotisations_types_cotisations WHERE actif = 1";
$dbresult = $db->Execute($query);
if($dbresult && $dbresult->RecordCount()>0)
{
	$i=0;
	while($row = $dbresult->FetchRow())
	{
		$i++;
		$nom_cotis = $row['nom'];
		$cotis_id = $row['id'];
		$cotiz_poss[] = $row['id'];
		$smarty->assign('cotiz'.$i,$nom_cotis);
	}
}
//var_dump($cotiz_poss);

//on va essayer d'établir le nb de colonnes nécessaires au tableau
$smarty->assign('nb_colonnes', $dbresult->recordCount());

$result= array();
$query = "SELECT adh.id,adh.nom, adh.prenom, adh.licence, adh.genid,adh.actif, be.id_cotisation, be.ref_action, be.reglement FROM ".cms_db_prefix()."module_adherents_adherents AS adh LEFT JOIN ".cms_db_prefix()."module_cotisations_belongs AS be   ON adh.genid = be.genid WHERE adh.actif = 1 ORDER BY adh.nom";
$dbresult= $db->Execute($query);
$rowarray= array();
	$rowclass = '';
	$cotis_ops = new cotisationsbis;
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				//les champs disponibles : 
				$genid = $row['genid'];
				$ref_action = $row['ref_action'];
				//On cherche le joli nom de la cotisation
				$nom_cotis = $cotis_ops->cotis_exists($row['id_cotisation']);				
				$actif = $row['actif'];
				$onerow->genid= $row['genid'];				
				$onerow->nom = $row['nom'];
				$onerow->prenom = $row['prenom'];
				$onerow->cotisation = $nom_cotis;
				$onerow->ref_action = $ref_action;
				
				if(true == $use_paiements)
				{
					$paiements_ops = new paiementsbis;
					$nb = $paiements_ops->marked_as_paid($ref_action);
				}
				else
				{
					$nb = $row['reglement'];
				}
				
				//var_dump($nb);
				if(TRUE == $nb)
				{
					$onerow->cotis_paid = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon');
					$onerow->delete = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon');
				}
				else
				{
					$onerow->cotis_paid = $this->CreateLink($id, 'cotisations', $returnid,$themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon'), array("obj"=>"payment","ref_action"=>$ref_action));
					$onerow->delete = $this->CreateLink($id, 'cotisations', $returnid,$themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array("obj"=>"delete_user_cotis", "ref_action"=>$ref_action));
				}
		
				$onerow->view= $this->CreateLink($id, 'view_adherent', $returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('edit'), '', '', 'systemicon'), array('genid'=>$row['genid']));
				$onerow->add_cotis= $this->CreateLink($id, 'add_cotis_to_adherent', $returnid, $themeObject->DisplayImage('icons/system/newobject.gif', $this->Lang('new'), '', '', 'systemicon'), array('genid'=>$row['genid']));
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}

			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		$smarty->assign('form2start',
				$this->CreateFormStart($id,'mass_action',$returnid));
		$smarty->assign('form2end',
				$this->CreateFormEnd());
		$articles = array("Activer"=>"activate", "Désactiver"=>"desactivate", "Rafraichir les données"=>"refresh");
		$smarty->assign('actiondemasse',
				$this->CreateInputDropdown($id,'actiondemasse',$articles));
		$smarty->assign('submit_massaction',
				$this->CreateInputSubmit($id,'submit_massaction',$this->Lang('apply_to_selection'),'','',$this->Lang('areyousure_actionmultiple')));
		
		

echo $this->ProcessTemplate('cotis_adherents.tpl');


#
# EOF
#
?>