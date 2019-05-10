<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
//debug_display($params, 'Parameters');
//$ping = cms_utils::get_module('Ping');
$ping_ops = new compositionsbis;
$liste_epreuves_equipes = $ping_ops->liste_epreuves();
//var_dump($liste_epreuves_equipes);
for($i=1; $i<=50;$i++)
{
//	echo $i;
	$liste_journees[$i] = $i;
}
//var_dump($liste_journees); 
$liste_phase = array("1"=>"1","2"=>"2");
$OuiNon = array("Brouillon"=>"0", "Propre"=>"1");
$edit = 0; //variable pour savoir s'il s'agit d'un ajout ou d'une modification
if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
		$edit = 1;
		$query = "SELECT id, idepreuve, journee, ref_action, actif, statut FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($record_id));
		$compt = 0;
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$compt++;
			$id = $row['id'];
			$ref_action = $row['ref_action'];
			$idepreuve = $row['idepreuve'];
			$journee = $row['journee'];			
			$actif = $row['actif'];
			$statut = $row['statut'];
		}
}
else
{
	$record_id = $this->random_string(10);
}
if(isset($idepreuve))	
{
	$key_idepreuve = array_values($liste_epreuves_equipes);//$index_paiement = $paiement;
	//var_dump($key_statut_commande);
	$key2_idepreuve = array_search($idepreuve,$key_idepreuve);
	//var_dump($key2_statut_commande);
}
else
{
	$key2_idepreuve = 0;
	$idepreuve = 0;
}
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_compo', $returnid ) );

	
	$smarty->assign('record_id',$this->CreateInputHidden($id,'record_id',$record_id));		

	$smarty->assign('edition',
			$this->CreateInputHidden($id,'edition',$edit));
	$smarty->assign('idepreuve',
			$this->CreateInputDropdown($id,'idepreuve',$liste_epreuves_equipes,$selectedIndex=$key2_idepreuve,$selectedvalue=$idepreuve));
	$smarty->assign('journee',
			$this->CreateInputDropdown($id,'journee',(isset($journee)?$journees:$liste_journees)));
	$smarty->assign('actif',
			$this->CreateInputDropdown($id,'actif',(isset($actif)?$actif:$OuiNon),50,200));						
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_compo.tpl');

#
# EOF
#
?>