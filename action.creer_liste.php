<?php
// cette page compose les différentes listes utilisées pour les compos d'équipes
if(!isset($gCms)) exit;
if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;   
}
//debug_display($params, 'Parameters');
$db =& $this->GetDb();
if(isset($params['idepreuve']) && $params['idepreuve'] != '')
{
	$idepreuve = $params['idepreuve'];
}
else
{
	//on redirige ?
}
//On va chercher la liste de tous les joueurs possibles du club
$smarty->assign('formstart',
		$this->CreateFormStart( $id, 'do_creer_liste', $returnid ) );
$smarty->assign('idepreuve',
		$this->CreateInputHidden($id,'idepreuve',$idepreuve));
$query = "SELECT j.licence, CONCAT_WS(' ',j.nom, j.prenom ) AS joueur ,j.points, j.sexe, j.cat FROM ".cms_db_prefix()."module_adherents_adherents AS j WHERE fftt = 1 AND actif = 1 AND type = 'T' ORDER BY j.points DESC,j.nom ASC ";
$dbresult = $db->Execute($query);
if($dbresult && $dbresult->RecordCount()>0)
{
	//On prépare le formulaire	
	//instanciation du module Ping
//	$ping = cms_utils::get_module('Ping');
	$ping = new ping_admin_ops;
	$annee_courante = date('Y');
	$mois_courant = date('m');
	while($row = $dbresult->FetchRow())
	{
		//var_dump($row);

		$licence = $row['licence'];
		if($mois_courant>=7)
		{
			$pts = $ping->get_sit_mens($licence, $mois_event=7, $annee_courante);
		}
		else
		{
			$pts = $ping->get_sit_mens($licence, $mois_event=1, $annee_courante);
		}
		//on va chercher les points officiels de ce joueur dans le module Ping
		$joueur = $row['joueur'].'   ('.$pts.' pts/ '.$row['sexe'].' / '.$row['cat'].')';
		$rowarray[$licence]['name'] = $joueur;
		$rowarray[$licence]['participe'] = false;

		//on va chercher si le joueur est déjà dans la table
		$query2 = "SELECT licence FROM ".cms_db_prefix()."module_compositions_listes_joueurs WHERE idepreuve = ? AND licence = ?";
		//echo $query2;
		$dbresultat = $db->Execute($query2, array($idepreuve,$licence));

		if($dbresultat->RecordCount()>0)
		{
			while($row2 = $dbresultat->FetchRow())
			{


				$rowarray[$licence]['participe'] = true;
			}
		}
		//print_r($rowarray);





	}
	$smarty->assign('rowarray',$rowarray);	

	
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('submitall',
			$this->CreateInputSubmit($id, 'submitall', $this->Lang('submitall'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));
	$smarty->assign('back',
			$this->CreateInputSubmit($id,'back',
						$this->Lang('back')));

	$smarty->assign('formend',
			$this->CreateFormEnd());
						
			
	
echo $this->ProcessTemplate('listes_joueurs.tpl');
}
else
{
	//Requete incorrecte ou pas de résultats
}
?>