<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
//debug_display($params, 'Parameters');

$adh_ops = new Asso_adherents;
$gp_ops = new groups;
$comp_ops = new compositionsbis;
$liste_adherents = $adh_ops->liste_adherents();
$liste_groupes = $gp_ops->liste_groupes();

if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;
		$query = "SELECT libequipe,friendlyname,idepreuve, nb_joueurs,capitaine, nb_joueurs, liste_id FROM ".cms_db_prefix()."module_compositions_equipes WHERE id = ?";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$libequipe = $row['libequipe'];
			$friendlyname = $row['friendlyname'];
			$nb_joueurs = $row['nb_joueurs'];
			$capitaine = $row['capitaine'];
			$idepreuve = $row['idepreuve'];
			$liste_id = $row['liste_id'];
		}
		$smarty->assign('record_id',$this->CreateInputHidden($id,'record_id',$record_id));
}
	
	$liste_epreuves = $comp_ops->liste_epreuves();
	

	$smarty->assign('idepreuve',
			$this->CreateInputDropdown($id, 'idepreuve',$liste_epreuves));
	

if(isset($capitaine))	
{
	$key_capitaine = array_values($liste_adherents);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_capitaine = array_search($capitaine,$key_capitaine);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_capitaine = 0;
	$capitaine = 0;
}
if(isset($liste_id))	
{
	$key_liste_id = array_values($liste_groupes);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_liste_id = array_search($liste_id,$key_liste_id);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_liste_id = 0;
	$liste_id = 1;
}
if(isset($idepreuve))	
{
	$key_idepreuve = array_values($liste_epreuves);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_idepreuve = array_search($idepreuve,$key_idepreuve);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_idepreuve = 0;
	$idepreuve = 0;
}


	$OuiNon = array("Non"=>"0", "Oui"=>"1");//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_equipe', $returnid ) );

	
			

	
	$smarty->assign('libequipe',
			$this->CreateInputText($id,'libequipe',(isset($libequipe)?$libequipe:""), 30, 150));
	$smarty->assign('friendlyname',
			$this->CreateInputText($id,'friendlyname',(isset($friendlyname)?$friendlyname:""), 30, 150));
	$smarty->assign('idepreuve',
			$this->CreateInputDropdown($id,'idepreuve',$liste_epreuves,$selectedIndex=$key2_idepreuve,$selectedvalue=$idepreuve));
	$smarty->assign('capitaine',
			$this->CreateInputDropdown($id,'capitaine',$liste_adherents,$selectedIndex=$key2_capitaine,$selectedvalue=$capitaine));
	$smarty->assign('liste_id',
					$this->CreateInputDropdown($id,'liste_id',$liste_groupes,$selectedIndex=$key2_liste_id,$selectedvalue=$liste_id));
	$smarty->assign('nb_joueurs',
			$this->CreateInputText($id,'nb_joueurs',(isset($nb_joueurs)?$nb_joueurs:""), 30, 150));
		
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('submitasnew',
			$this->CreateInputSubmit($id, 'submitasnew', $this->Lang('submitasnew'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_equipe.tpl');

#
# EOF
#
?>