<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class Compositionsbis
{
  function __construct() {}


##
##
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
	function nb_equipes_idepreuve($idepreuve,$phase)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$ping = cms_utils::get_module('Ping');
		$saison = $ping->GetPreference('saison_en_cours');
		$query = "SELECT count(*) AS nb FROM ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ? AND saison = ? AND phase = ?";
		$dbresult = $db->Execute($query, array($idepreuve,$saison, $phase));
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
		$query = "SELECT licence FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[] = $row['licence'];
			}
			return $retour;
		}
		else
		{
			return FALSE;
		}
		
	}
	// Cette fonction récupère  toutes les licences disponibles (non encore affectées à cette journée)
	// et exclue celles déjà affectées pour cette équipe, pour une réédition d'une composition en fait
	function licences_disponibles($ref_action,$ref_equipe)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT licence FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe != ?";
		$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[] = $row['licence'];
			}
			return $retour;
		}
		else
		{
			return FALSE;
		}
		
	}
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
	function get_phase($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT phase FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$phase = $row['phase'];
			
				return $phase;			
			
		}
		else
		{
			return FALSE;
		}
	}
	function get_saison($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT saison FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			$row = $dbresult->FetchRow();
			$saison = $row['saison'];
			
				return $saison;
			
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
		$query = "SELECT friendlyname, libequipe FROM ".cms_db_prefix()."module_compositions_equipes WHERE ref_equipe = ?";
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
		$query = "SELECT licence FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
		
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				$retour[] = $row['licence'];
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
		$query = "SELECT count(licence) AS nb FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ?";
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
	// Cette fonction supprime toutes les compos d'une ref_action
	function delete_compo($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
	}
	//Cette fonction supprime la journée 
	function delete_journee($ref_action)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "DELETE FROM ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($ref_action));
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
	function duplicate_journee($neo_ref_action, $phase, $journee, $idepreuve, $saison)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_journees (ref_action,idepreuve,journee,phase, saison) VALUES(?,?,?,?, ?)";
		$dbresult = $db->Execute($query, array($neo_ref_action,$idepreuve, $journee,$phase, $saison));
		if($dbresult)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	function duplicate($neo_ref_action,$ref_action, $ref_equipe, $licence)
	{
		global $gCms;
		$db = cmsms()->GetDb();	
		
		$query = "INSERT INTO ".cms_db_prefix()."module_compositions_compos_equipes (ref_action, ref_equipe, licence) VALUES (?,?,?)";
		$dbresult = $db->Execute($query, array($neo_ref_action, $ref_equipe, $licence));

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
	function player_by_idepreuve ($idepreuve,$phase)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		
		$ping = cms_utils::get_module('Ping');
		$saison = $ping->GetPreference('saison_en_cours');
		$query = "SELECT SUM(nb_joueurs) AS nb FROM ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ? AND saison = ? AND phase = ?";
		$dbresult = $db->Execute($query, array($idepreuve,$saison, $phase));
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
	function pourcentage_remplissage($ref_action)
	{
		$phase = $this->get_phase($ref_action);
		$epreuve = $this->get_idepreuve($ref_action);
		$total_players = $this->player_by_idepreuve($epreuve, $phase);
		$already_used = $this->nb_already_used_licences($ref_action);
		$pourcentage = round(($already_used*100)/$total_players, 2);
		return $pourcentage;
	}
	 function liste_exists($idepreuve)
	{
		global $gCms;
		$db = cmsms()->GetDb();
		$query = "SELECT SUM(idepreuve) AS nb FROM ".cms_db_prefix()."module_compositions_listes_joueurs WHERE idepreuve = ?";
		$dbresult = $db->Execute($query, array($idepreuve,$saison, $phase));
		if($dbresult && $dbresult->RecordCount() >0)
		{
			
			return TRUE;
		}
		else
		{
			//pas de résultats, on renvoit FALSE
			return FALSE;
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