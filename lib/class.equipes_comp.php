<?php
#CMS - CMS Made Simple
#(c)2004 by Ted Kulp (wishy@users.sf.net)
#This project's homepage is: http://www.cmsmadesimple.org


class equipes_comp
{
	function __construct() {}
	
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
	function details_equipe($record_id)
	{
		$db = cmsms()->GetDb();
		$query = "SELECT id, libequipe, friendlyname, idepreuve, nb_joueurs, liste_id FROM ".cms_db_prefix()."module_compositions_equipes WHERE id = ?"; 
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
#END OF CLASS
	
}
