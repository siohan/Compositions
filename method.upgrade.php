<?php
#-------------------------------------------------------------------------
# Module: Compositions
# Version: 0.1
# Method: Upgrade
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2008 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
# The module's homepage is: http://dev.cmsmadesimple.org/projects/skeleton/
#
#-------------------------------------------------------------------------

/**
 * For separated methods, you'll always want to start with the following
 * line which check to make sure that method was called from the module
 * API, and that everything's safe to continue:
*/ 
if (!isset($gCms)) exit;

$db = $this->GetDb();			/* @var $db ADOConnection */
$dict = NewDataDictionary($db); 	/* @var $dict ADODB_DataDict */

$now = trim($db->DBTimeStamp(time()), "'");
$current_version = $oldversion;
switch($oldversion)
{
  // we are now 1.0 and want to upgrade to latest
 
	
	case "0.1" : 	
	{
		$flds = "
			id I(11) AUTO KEY,
			licence I(11),
			date_debut D,
			date_fin D,
			motif T";
			$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_compositions_absences", $flds);
			$dict->ExecuteSQLArray($sqlarray);			
		//
	
		
	}
	case "0.2" :
	{
			$dict = NewDataDictionary( $db );
			$flds = "J1 I(2) DEFAULT 0,
				J2 I(2) DEFAULT 0,
				J3 I(2) DEFAULT 0,
				J4 I(2) DEFAULT 0,
				J5 I(2) DEFAULT 0,
				J6 I(2) DEFAULT 0,
				J7 I(2) DEFAULT 0,
				J8 I(2) DEFAULT 0,
				J9 I(2) DEFAULT 0,
				J10 I(2) DEFAULT 0,
				J11 I(2) DEFAULT 0,
				J12 I(2) DEFAULT 0,
				J13 I(2) DEFAULT 0,
				J14 I(2) DEFAULT 0";

			$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_compositions_brulage", $flds);
			$dict->ExecuteSQLArray($sqlarray);
	}
	
	case "0.2.1" :
	{
		// table schema description
		$flds = "
			id I(11) AUTO KEY,
			idepreuve I(4),
			libelle C(255),
			description C(255),
			actif I(1) DEFAULT '1'";
			$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_compositions_epreuves", $flds);
			$dict->ExecuteSQLArray($sqlarray);			
		//
		$idxoptarray = array('UNIQUE');
		$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'epreuves',
				    		cms_db_prefix().'module_compositions_epreuves', 'idepreuve',$idxoptarray);
		$dict->ExecuteSQLArray($sqlarray);
		
		//on supprime la table brulage
		$dict = NewDataDictionary($db);
		$sqlarray = $dict->DropTableSql(cms_db_prefix()."module_compositions_absences");
		$dict->ExecuteSQLArray($sqlarray);
		
		// 1 - on supprime l'index cms_compos_equipes ref_action, ref_equipe, licence
		// 2 - on ajoute le champ genid
		// 3 - on remplace les licences par le genid
		// 4 - on créé l'index cms_compos_equipes  id_cotisation et genid
		
		// 1 - 
		$sqlarray = $dict->DropIndexSQL(cms_db_prefix().'compos_equipes',
			    cms_db_prefix().'module_compositions_compos_equipes');
		$dict->ExecuteSQLArray($sqlarray);
		
		// 2 - 
		//on créé un nouveau champ genid I(11) pour la table cotisations_belongs
		$dict = NewDataDictionary( $db );
		$flds = "genid I(11)";
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_compositions_compos_equipes", $flds);
		$dict->ExecuteSQLArray($sqlarray);
		
		// 3 - on remplace les licences par le genid
		//on remplace les licences par le genid
		$query = "SELECT adh.genid, be.licence FROM ".cms_db_prefix()."module_adherents_adherents AS adh, ".cms_db_prefix()."module_compositions_compos_equipes AS be WHERE adh.licence = be.licence";
		$dbresult = $db->Execute($query);
		if($dbresult)
		{
			while($row = $dbresult->FetchRow())
			{
				$genid = $row['genid'];
				$query2 = "UPDATE ".cms_db_prefix()."module_compositions_compos_equipes SET genid = ? WHERE licence = ?";
				$dbresult2 = $db->Execute($query2, array($genid, $row['licence']));
			
			}
		}
		
		// 4 - on recrée un index compos_equipes
		$idxoptarray = array('UNIQUE');
		$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'compos_equipes',
			    cms_db_prefix().'module_compositions_compos_equipes', 'ref_action,ref_equipe,genid',$idxoptarray);
		$dict->ExecuteSQLArray($sqlarray);
		
		
			
			// 1 - on ajoute le champ genid
			// 2 - on remplace les licences par le genid
		

		

			// 1- 
			//on créé un nouveau champ genid I(11) pour la table cotisations_belongs
			$dict = NewDataDictionary( $db );
			$flds = "genid I(11)";
			$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_compositions_listes_joueurs", $flds);
			$dict->ExecuteSQLArray($sqlarray);

			// 2 - on remplace les licences par le genid
			//on remplace les licences par le genid
			$query = "SELECT adh.genid, be.licence FROM ".cms_db_prefix()."module_adherents_adherents AS adh, ".cms_db_prefix()."module_compositions_listes_joueurs AS be WHERE adh.licence = be.licence";
			$dbresult = $db->Execute($query);
			if($dbresult)
			{
				while($row = $dbresult->FetchRow())
				{
					$genid = $row['genid'];
					$query2 = "UPDATE ".cms_db_prefix()."module_compositions_listes_joueurs SET genid = ? WHERE licence = ?";
					$dbresult2 = $db->Execute($query2, array($genid, $row['licence']));

				}
			}

		
	}
	
	case "0.3" : 
	case "0.4" :
	{
		$this->SetPreference('pageid_compositions','');
		$flds = "date_limite I(11) DEFAULT 0";
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_compositions_journees", $flds);
		$dict->ExecuteSQLArray($sqlarray);
		
		
		$flds = "timbre I(11) DEFAULT 0";
		$sqlarray = $dict->AddColumnSQL( cms_db_prefix()."module_compositions_compos_equipes", $flds);
		$dict->ExecuteSQLArray($sqlarray);
		
		$this->SetPreference('sms_sender','Expéditeur');
		$this->SetPreference('use_messages','0');
	}
}


// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('upgraded', $this->GetVersion()));

//note: module api handles sending generic event of module upgraded here
?>