<?php
#-------------------------------------------------------------------------
# Module: Compositions
# Version: 0.1, Claude SIOHAN Agi webconseil
# Method: Install
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


/** 
 * After this, the code is identical to the code that would otherwise be
 * wrapped in the Install() method in the module body.
 */

$db = $gCms->GetDb();

// mysql-specific, but ignored by other database
$taboptarray = array( 'mysql' => 'ENGINE=MyISAM' );
$dict = NewDataDictionary( $db );

// table schema description
$flds = "
	id I(11) AUTO KEY,
	ref_action C(15),
	idepreuve I(4),
	journee I(2),
	date_created D,
	actif I(1) DEFAULT 0,
	statut I(1) DEFAULT 0";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_compositions_journees", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// table schema description
$flds = "
	id I(11) AUTO KEY,
	ref_equipe C(15),
	numero_equipe I(2),
	libequipe C(100),
	friendlyname C(15),
	idepreuve I(4),
	liste_id I(3),
	capitaine I(10),
	nb_joueurs I(1) DEFAULT 0";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_compositions_equipes", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// table schema description
$flds = "
	id I(11) AUTO KEY,
	libelle C(255),
	description C(255),
	actif I(1) DEFAULT '1'";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_compositions_epreuves", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//
// table schema description
$flds = "
	id I(11) AUTO KEY,
	ref_action C(10),
	ref_equipe C(15),
	genid I(10),
	statut I(1) DEFAULT 0 ";
	$sqlarray = $dict->CreateTableSQL( cms_db_prefix()."module_compositions_compos_equipes", $flds, $taboptarray);
	$dict->ExecuteSQLArray($sqlarray);			
//			

//
#Indexes
//on créé un index sur la table
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'epreuves_journees',
	    			cms_db_prefix().'module_compositions_journees', 'idepreuve, journee',$idxoptarray);
$dict->ExecuteSQLArray($sqlarray);
#
//on créé un index sur la table
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'equipes',
	    			cms_db_prefix().'module_compositions_equipes', 'idepreuve, ref_equipe',$idxoptarray);
$dict->ExecuteSQLArray($sqlarray);
#
//on créé un index sur la table
$idxoptarray = array('UNIQUE');
$sqlarray = $dict->CreateIndexSQL(cms_db_prefix().'compos_equipes',
		    		cms_db_prefix().'module_compositions_compos_equipes', 'ref_action, ref_equipe, genid',$idxoptarray);
$dict->ExecuteSQLArray($sqlarray);
			#
//Permissions
$this->CreatePermission('Compositions use', 'Compositions : utiliser le module');
$this->CreatePermission('Compositions lock', 'Compositions : Verrouiller');
$this->CreatePermission('Compositions unlock', 'Compositions : Déverrouiller');

# Mails templates
$fn = cms_join_path(dirname(__FILE__),'templates','orig_relance_email.tpl');
if( file_exists( $fn ) )
{
	$template = file_get_contents( $fn );
	$this->SetTemplate('relance_email',$template);
}
# Les préférences 

$this->SetPreference('admin_email', 'root@localhost.com');
$this->SetPreference('sujet_relance_email','[A.S] Ton équipe...');


// put mention into the admin log
$this->Audit( 0, 
	      $this->Lang('friendlyname'), 
	      $this->Lang('installed', $this->GetVersion()) );

	
	      
?>