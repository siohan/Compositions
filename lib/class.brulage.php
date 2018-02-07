<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class brulage
{
	function __construct() {}
	
	//vérifie si le joueur est déjà ds la table brulage
	function is_already_there($idepreuve,$licence, $phase, $saison)
	{
		global $gCms;
		$db= cmsms()->GetDb();
		$ping = cms_utils::get_module('Ping');
		$phase = $ping->GetPreference('phase_en_cours');
		$saison = $ping->GetPreference('saison_en_cours');
		$query = "SELECT count(*) AS nb FROM ".cms_db_prefix()."module_compositions_brulage WHERE idepreuve = ? AND licence = ? AND phase = ? AND saison = ?";
		$dbresult = $db->Execute($query, array($idepreuve, $licence, $phase, $saison));
		if($dbresult)
		{
			// la requete fonctionne ! Ouf !
			//des résultats ?
			$row = $dbresult->FetchRow();
			$nb = $row['nb'];
			if($nb >0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
	}
	//ajoute un joueur dans la table brulage
	function add_player($idepreuve, $licence)
	{
		global $gCms;
		$db= cmsms()->GetDb();
		$ping = cms_utils::get_module('Ping');
		$phase = $ping->GetPreference('phase_en_cours');
		$saison = $ping->GetPreference('saison_en_cours');
		$query = " INSERT INTO ".cms_db_prefix()."module_compositions_brulage (idepreuve, licence, phase, saison) VALUES (?, ?, ?, ?)";
		$dbresult = $db->Execute($query, array($idepreuve, $licence, $phase, $saison));
	}
	// insère les données d'une journée (licence, epreuve, J1,J2,...) dans la table brulage
	//c'est une modif
	function brulage($ref_action)
	{
		global $gCms;
		$db= cmsms()->GetDb();
		$query = "SELECT cp.licence, cp.ref_action, cp.ref_equipe  FROM ".cms_db_prefix()."module_compositions_compos_equipes AS cp, ".cms_db_prefix()."module_compositions_equipes AS eq WHERE cp.ref_equipe = eq.ref_equipe AND cp.ref_action = ? ORDER BY eq.numero_equipe ASC";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			//on récupère la journée, la saison, l'équipe aussi
			$comp_ops = new compositionsbis;
			$saison = $comp_ops->get_saison($ref_action);
			$journee = $comp_ops->get_journee($ref_action);
			$phase = $comp_ops->get_phase($ref_action);
			$idepreuve = $comp_ops->get_idepreuve($ref_action);
			//on déduit la journee pour savoir quelle colonne modifier
			$colonne = 'J'.$journee;
			//on peut faire la boucle
			while($row = $dbresult->FetchRow())
			{
				$licence = $row['licence'];
				$numero_equipe = $row['ref_equipe'];
				$ajout = $this->is_already_there($idepreuve, $licence, $phase, $saison);
				if(FALSE === $ajout)
				{
					$query2 = "UPDATE ".cms_db_prefix()."module_compositions_brulage SET $colonne = ? WHERE licence = ? AND idepreuve = ? AND phase = ? AND saison = ?";
					$dbresult2 = $db->Execute($query2, array($numero_equipe, $licence, $idepreuve, $phase, $saison));
				}
				else
				{
					$query2 = "INSERT INTO ".cms_db_prefix()."module_compositions_brulage (idepreuve, licence, $colonne, phase, saison) VALUES (?, ?, ?, ?, ?)";
					$dbresult2 = $db->Execute($query2, array($idepreuve, $licence, $numero_equipe, $phase, $saison));
				}//$numero_equipe = $comp_ops->get_equipe_official_number($row['ref_equipe']);
				//var_dump($numero_equipe);
				//on insère maintenant
				
			}
			
		}
	}
	
}
