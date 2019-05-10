<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class Compositionsbis
{
  function __construct() {}


##
##
	//récupère les détails d'une ref_action
	function details_ref_action($ref_action)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT id,  FROM ".cms_db_prefix()."module_compositions_equipes WHERE id = ?"; 
		$dbresult = $db->Execute($query, array($record_id));
		if($dbresult)
		{
			$details = array();
			while($row = $dbresult->FetchRow())
			{
				$details['id'] = $row['id'];
				$details['libequipe'] = $row['libequipe'];
				$details['friendlyname'] = $row['friendlyname'];
				$details['idepreuve'] = $row['idepreuve'];
				$details['nb_joueurs'] = $row['nb_joueurs'];
				$details['liste_id'] = $row['liste_id'];
			}
			return $details;
		}
		else
		{ 
			return false;
		}
	}
	//Cette fonction verrouille les toutes les compositions d'une journée
	public function  lock($ref_action)
	{
		global $gCms; 
		$db = cmsms()->GetDb();	
		$query = "UPDATE ".cms_db_prefix()."module_compositions_journees SET statut = 1 WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult)
		{
			$query2 = "UPDATE ".cms_db_prefix()."module_compositions_compos_equipes SET statut = 1 WHERE ref_action = ?";
			$dbresult2 = $db->Execute($query2, array($ref_action));
			return TRUE;
		}
		else
		{
			$error = $db->ErrorMsg();
			return $error;
		}
	}
	//Cette fonction déverrouille les toutes les compositions d'une journée
	function unlock($ref_action)
	{
		global $gCms;
			//$ping = cms_utils::get_module('paiements'); 
		$db = cmsms()->GetDb();	
		$query = "UPDATE ".cms_db_prefix()."module_compositions_journees SET statut = 0 WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			$error = $db->ErrorMsg();
			return $error;
		}
	}
	//Cette fonction verrouille la composition d'une équipe
	public function  lock_equipe($ref_action, $ref_equipe)
	{
		global $gCms; 
		$db = cmsms()->GetDb();	
		$query = "UPDATE ".cms_db_prefix()."module_compositions_compos_equipes SET statut = 1 WHERE ref_action = ? AND ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			$error = $db->ErrorMsg();
			return $error;
		}
	}
	//Cette fonction déverrouille la composition d'une équipe
	function unlock_equipe($ref_action, $ref_equipe)
	{
		global $gCms;
		//$ping = cms_utils::get_module('paiements'); 
		$db = cmsms()->GetDb();	
		$query = "UPDATE ".cms_db_prefix()."module_compositions_compos_equipes SET statut = 0 WHERE ref_action = ? AND ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			$error = $db->ErrorMsg();
			return $error;
		}
	}
	//Cette fonction calcule le nb d'équipes pour une épreuve et une phase donnée
	function nb_equipes_idepreuve($idepreuve)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT count(*) AS nb FROM ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ?";
		$dbresult = $db->Execute($query, array($idepreuve));
		if($dbresult && $dbresult->RecordCount() >0)
		{
			//on retourne le nb d'équipes
			$row = $dbresult->FetchRow();
			$nb = $row['nb'];
			return $nb;
		}
		else
		{
			//pas de résultats, on renvoit FALSE
			return FALSE;
		}
	}
	function equipe_par_defaut($ref_action)
	{
		
	}
	// Cette fonction récupère les licences d'une composition d'une équipe donnée pour une journée donnée
	function licences_by_ref_equipe($ref_action,$ref_equipe)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT genid FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[] = $row['genid'];
			}
			return $retour;
		}
		else
		{
			return FALSE;
		}
		
	}
	// Cette fonction récupère  tous les genid disponibles (non encore affectées à cette journée)
	// et exclue celles déjà affectées pour cette équipe, pour une réédition d'une composition en fait
	function licences_disponibles($ref_action,$ref_equipe)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT genid FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe != ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[] = $row['genid'];
			}
			return $retour;
		}
		else
		{
			return FALSE;
		}
		
	}
	/*
	function restrictions_clt_mini($ref_equipe)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT clt_mini, points_maxi FROM ".cms_db_prefix()."module_compositions_equipes WHERE ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$clt_mini = $row['clt_mini'];
			$points_maxi = $row['points_maxi'];
			if($clt_mini >0)
			{
				return $clt_mini;
			}
			else
			{
				return FALSE;
			}
			
		}
		
	}
	*/
	function get_idepreuve($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT idepreuve FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$idepreuve = $row['idepreuve'];
			
			return $idepreuve;
		}
		else
		{
			return FALSE;
		}
	}
	
	function get_journee($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT journee FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$journee = $row['journee'];
			
				return $journee;
			
		}
		else
		{
			return FALSE;
		}
	}
	function get_equipe($ref_equipe)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT friendlyname, libequipe FROM ".cms_db_prefix()."module_compositions_equipes WHERE id = ?";
		$dbresult = $db->Execute($query, array($ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$result['friendlyname'] = $row['friendlyname'];
			$result['libequipe'] = $row['libequipe'];
			return $result;
		}
		else
		{
			return FALSE;
		}
	}
	//récupère le numéro officiel de l'équipe'
	function get_equipe_official_number($ref_equipe)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT numero_equipe FROM ".cms_db_prefix()."module_compositions_equipes WHERE ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$numero_equipe = $row['numero_equipe'];
			return $numero_equipe;
		}
		else
		{
			return FALSE;
		}
	}
	//sélectionne les licences déjà utilisées dans une ref_action
	function already_used_licences($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT genid FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[] = $row['genid'];
			}
			return $retour;
		}
		else
		{
			return FALSE;
		}
	}
	//compte le nb de licences utilisées dans une ref_action
	function nb_already_used_licences($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT count(genid) AS nb FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		
		if($dbresult && $dbresult->RecordCount()>0)
		{
			//on retourne le nb d'équipes
			$row = $dbresult->FetchRow();
			$nb = $row['nb'];
			return $nb;
		}
		else
		{
			//pas de résultats, on renvoit FALSE
			return FALSE;
		}
	}
	//supprime la compo d'une équipe donnée
	function delete_compo_equipe($ref_action, $ref_equipe)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
	}
	//supprime un joueur d'une ref_actionla compo d'une équipe donnée
	function delete_joueur($genid,$ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND genid = ?";
		$dbresult = $db->Execute($query, array($ref_action, $genid));
	}
	// Cette fonction supprime toutes les compos d'une ref_action
	function delete_compo($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
	}
	//Supprime la journée 
	function delete_journee($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
	}
	//Supprime une équipe purement et simplement 
	function delete_eq($record_id)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_equipes WHERE id = ?";
		$dbresult = $db->Execute($query, array($record_id));
	}
	// change le statut d'une ref_action en actif ou non
	function actif($ref_action, $actif)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		if($actif == 1)
		{
			$query = "UPDATE ".cms_db_prefix()."module_compositions_journees SET actif = 1 WHERE ref_action = ?";
			$dbresult = $db->Execute($query, array($ref_action));
		}
		else
		{
			$query = "UPDATE ".cms_db_prefix()."module_compositions_journees SET actif = 0 WHERE ref_action = ?";
			$dbresult = $db->Execute($query, array($ref_action));
		}
	}
	//duplique une journée
	function duplicate_journee($neo_ref_action, $journee, $idepreuve)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_journees (ref_action,idepreuve,journee) VALUES( ?,?,?)";
		$dbresult = $db->Execute($query, array($neo_ref_action,$idepreuve, $journee));
		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	//duplique une journée (journée + 1)
	function duplicate($neo_ref_action,$ref_action, $ref_equipe, $genid)
	{
		global $gCms;
		$db = cmsms()->GetDb();	
		
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_compos_equipes (ref_action, ref_equipe, genid) VALUES (?,?,?)";
		$dbresult = $db->Execute($query, array($neo_ref_action, $ref_equipe, $genid));

		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	//duplique une équipe
	function duplicate_team($record_id)
	{
		global $gCms;
		$db = cmsms()->GetDb();	
		
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_compos_equipes (ref_action, ref_equipe, genid) VALUES (?,?,?)";
		$dbresult = $db->Execute($query, array($neo_ref_action, $ref_equipe, $genid));

		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	//Cette fonction compte le nb de joueurs pour une épreuve
	function player_by_idepreuve ($idepreuve)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT SUM(nb_joueurs) AS nb FROM ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ?";
		$dbresult = $db->Execute($query, array($idepreuve));
		if($dbresult && $dbresult->RecordCount() >0)
		{
			//on retourne le nb d'équipes
			$row = $dbresult->FetchRow();
			$nb = $row['nb'];
			return $nb;
		}
		else
		{
			//pas de résultats, on renvoit FALSE
			$nb = 0;
			return $nb;
		} 
	}
	//calcule le pourcentage de remplissage des compositions d'équipes
	function pourcentage_remplissage($ref_action)
	{
		//$phase = $this->get_phase($ref_action);
		$epreuve = $this->get_idepreuve($ref_action);
		$total_players = $this->player_by_idepreuve($epreuve);
		$already_used = $this->nb_already_used_licences($ref_action);
		//$pourcentage = round(($already_used*100)/$total_players, 2);
		if($total_players == 0)
		{
			$pourcentage = 0;
		}
		else
		{
			$pourcentage = round(($already_used*100)/$total_players, 2);
		}
		
		return $pourcentage;
	}
	//vérifie si une liste de joueurs est déjà existante ou non pour une épreuve donnée
	 function liste_exists($idepreuve)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT SUM(idepreuve) AS nb FROM ".cms_db_prefix()."module_compositions_listes_joueurs WHERE idepreuve = ?";
		$dbresult = $db->Execute($query, array($idepreuve));
		$row = $dbresult->FetchRow();
		$nb = $row['nb'];
		
		return $nb;
		
	}
	//Retourne une liste de membres pour une équipe
	function liste_equipe($id)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_absences WHERE id = ?";
		$db->Execute($query, array($id));
	}
	//récupère les licences des capitaines d'équipes
	function capitaines()
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT DISTINCT capitaine FROM ".cms_db_prefix()."module_compositions_equipes";
		$dbresult = $db->Execute($query);
		if($dbresult)
		{
			$licences = array();
			while($row = $dbresult->fetchRow())
			{
				$licences[] = $row['capitaine'];
			}
			return $licences;
			
		}
		
	}
	#
	#
	#LES EPREUVES
	//ajoute une nouvelle épreuve
	function add_epreuve($libelle, $description, $actif)
	{
		$db = cmsms()->GetDb();
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_epreuves (libelle, description, actif) VALUES ( ?, ?, ?)";
		$dbresult = $db->Execute($query, array($libelle, $description, $actif));
		if($dbresult)
		{
			return true;

		}
		else
		{
			return false;
		}
	}
	//modifie une épreuve existante
	function update_epreuve($record_id, $libelle, $description, $actif)
	{
		$db = cmsms()->GetDb();
		$query = "UPDATE ".cms_db_prefix()."module_compositions_epreuves SET libelle = ?, description = ?, actif = ? WHERE id = ? ";//" VALUES (?, ?, ?, ?)";
		$dbresult = $db->Execute($query, array($libelle, $description, $actif, $record_id));
		if($dbresult)
		{
			return true;

		}
		else
		{
			return false;
		}
	}
	function details_epreuve($record_id)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT id, libelle, description,actif FROM ".cms_db_prefix()."module_compositions_epreuves WHERE id = ?";
		$dbresult = $db->Execute($query, array($record_id));
		$details_epreuve = array();
		while ($dbresult && $row = $dbresult->FetchRow())
		{
			$details_epreuve['id'] = $row['id'];
			$details_epreuve['libelle'] = $row['libelle'];
			$details_epreuve['description'] = $row['description'];
			$details_epreuve['actif'] = $row['actif'];					
		}		
		return $details_epreuve;
	}
	//Cette fonction liste les épreuves par équipes
	public function liste_epreuves()
	{
		$db = cmsms()->GetDb();
		$query = "SELECT libelle, id FROM  ".cms_db_prefix()."module_compositions_epreuves WHERE actif = '1' ORDER BY libelle ASC";
		$dbresult = $db->Execute($query);
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$epreuve[$row['libelle']] = $row['id'];
				
			}
			return $epreuve;
		}
		
	}
	//donne le nom d'une compétition
	function nom_compet($idepreuve)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT libelle FROM ".cms_db_prefix()."module_compositions_epreuves WHERE id = ?";
		$dbresult = $db->Execute($query, array($idepreuve));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$libelle = $row['libelle'];
			}
			return $libelle;
		}
		else
		{
			return false;
		}
	}
	//les imports des autres modules
	//ajoute une épreuve depuis le module Ping
	function add_epreuve_from_ping($idepreuve, $nom)
	{
		$db = cmsms()->GetDb();
		$actif = 1;
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_epreuves (id,libelle,actif) VALUES ( ?, ?, ?)";
		$dbresult = $db->Execute($query, array($idepreuve, $nom,$actif));
		if($dbresult)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function add_teams_from_ping($libequipe, $friendlyname,$nb_joueurs, $idepreuve)
	{
		$db = cmsms()->GetDb();
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_equipes (libequipe, friendlyname,nb_joueurs,idepreuve) VALUES (?, ?, ?, ?)";
		$dbresult = $db->Execute($query, array($libequipe, $friendlyname,$nb_joueurs, $idepreuve));
		if($dbresult)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
#
#
#
}//end of class
#
# EOF
#
?>