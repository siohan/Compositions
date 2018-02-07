<?php

if(!isset($gCms)) exit;
$db =& $this->GetDb();

if(!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
/*
$smarty->assign('form_start',
		$this->CreateFormStart());
*/		

$ping = cms_utils::get_module('Ping');
$parms = array();
$saison = $ping->GetPreference('saison_en_cours');
$phase = $ping->GetPreference('phase_en_cours');
$query = "SELECT id,saison,phase, libequipe, friendlyname, numero_equipe, idepreuve FROM ".cms_db_prefix()."module_ping_equipes WHERE saison = ?"; 
$parms['saison'] = $saison;
$dbresult = $db->Execute($query, $parms);//array($saison, $aujourdhui,$nom_equipes));
$i = 0; //on instancie un compteur
if($dbresult && $dbresult->RecordCount()>0)
{
	while($row = $dbresult->FetchRow())
	{
		
		$ref_equipe = $row['id'];
		$saison = $row['saison'];
		$phase = $row['phase'];
		$libequipe = $row['libequipe'];
		$friendlyname = $row['friendlyname'];
		$numero_equipe = $row['numero_equipe'];
		$idepreuve = $row['idepreuve'];
		$query2 = "INSERT INTO ".cms_db_prefix()."module_compositions_equipes (ref_equipe, numero_equipe,libequipe, friendlyname, idepreuve, phase, saison) VALUES( ?, ?, ?, ?, ?, ?, ?)";
		$dbresult2 = $db->Execute($query2, array($ref_equipe, $numero_equipe,$libequipe, $friendlyname, $idepreuve, $phase, $saison));
		$i++;
		if(!$dbresult2)
		{
			echo $this->ErrorMsg();
		}
	}
}
$message = $i. "equipe(s) insérée(s)";
$this->SetMessage($message);
$this->RedirectToAdminTab('equipes');





#
#EOF
#
?>