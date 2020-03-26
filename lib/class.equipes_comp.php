<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class equipes_comp
{
	function __construct() {}
	
	function details_equipe($record_id)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT id, ref_equipe, numero_equipe, libequipe, friendlyname, idepreuve,capitaine, nb_joueurs, liste_id FROM ".cms_db_prefix()."module_compositions_equipes WHERE id = ?"; 
		$dbresult = $db->Execute($query, array($record_id));
		if($dbresult)
		{
			$details = array();
			while($row = $dbresult->FetchRow())
			{
				$details['id'] = $row['id'];
				$details['ref_equipe'] = $row['ref_equipe'];
				$details['numero_equipe'] = $row['numero_equipe'];
				$details['libequipe'] = $row['libequipe'];
				$details['friendlyname'] = $row['friendlyname'];
				$details['idepreuve'] = $row['idepreuve'];
				$details['liste_id'] = $row['liste_id'];
				$details['capitaine'] = $row['capitaine'];
				$details['nb_joueurs'] = $row['nb_joueurs'];

			}
			return $details;
		}
		else
		{ 
			return false;
		}
	}
	//ajoute une nouvelle équipe
	function add_team($libequipe, $friendlyname, $idepreuve, $capitaine, $nb_joueurs, $liste_id)
	{
		global $gCms;
		$db= cmsms()->GetDb();
		
		$query = "INSERT INTO  ".cms_db_prefix()."module_compositions_equipes ( libequipe, friendlyname, idepreuve, capitaine, nb_joueurs, liste_id) VALUES ( ?, ?, ?, ?, ?, ?)";
		$dbresult = $db->Execute($query, array($libequipe, $friendlyname, $idepreuve, $capitaine, $nb_joueurs, $liste_id));		
		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		
	}
	//ajoute une nouvelle équipe
	function update_team($libequipe, $friendlyname, $idepreuve, $capitaine, $nb_joueurs, $liste_id, $record_id)
	{
		global $gCms;
		$db= cmsms()->GetDb();
		
		$query = "UPDATE  ".cms_db_prefix()."module_compositions_equipes SET libequipe = ?, friendlyname = ?, capitaine = ?, nb_joueurs = ?, idepreuve = ?, liste_id = ?  WHERE id = ?";
		$dbresult = $db->Execute($query, array($libequipe, $friendlyname, $capitaine, $nb_joueurs, $idepreuve, $liste_id, $record_id));	
		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
		
	}
	
	//vérifie si une équipe est complète
	function is_complete($ref_action, $ref_equipe)
	{
		//on doit récupérer le nb minimum de cette équipe
		$details = $this->details_equipe($ref_equipe);
		$mini = $details['nb_joueurs'];
		
		$nb_joueurs = $this->nb_players_in_team($ref_action,$ref_equipe);
		
		if($nb_joueurs < $mini)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	//retourne le nb de joueurs réellement saisi ds une équipe pour une ref_action donnée
	function nb_players_in_team($ref_action, $ref_equipe)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT count(*) AS nb FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult)
		{
			$row = $dbresult->FetchRow();
			$nb = $row['nb'];
			return $nb;
		}
		else
		{
			return false;
		}
		
	}
	function is_locked($ref_action, $ref_equipe)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT * FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ? AND statut = '1'";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
#END OF CLASS
	
}
