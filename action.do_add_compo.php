<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Compositions use'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('compos');
    		return;
  	}

debug_display($params, 'Parameters');
$db =& $this->GetDb();
$now = date('Y-m-d');

$designation = '';//le message final
$error = 0;//on initie un compteur d'erreur, 0 par défaut

if(isset($params['edition']) && $params['edition'] != '')
{
	$edit = $params['edition'];
}
else
{
	$edit = 0;//il s'agit d'un ajout de commande
}



if(isset($params['record_id']) && $params['record_id'] != '')
{
	$ref_action = $params['record_id'];
}
if(isset($params['idepreuve']) && $params['idepreuve'] != '')
{
	$idepreuve = $params['idepreuve'];
}
if(isset($params['journee']) && $params['journee'] != '')
{
	$journee = $params['journee'];
}

$actif = '';
if(isset($params['actif']) && $params['actif'] != '')
{
	$actif = $params['actif'];
}

$statut = 0;
if(isset($params['statut']) && $params['statut'] != '')
{
	$statut = $params['statut'];
}


if($edit == 0)
{
	//on fait d'abord l'insertion 
	$query1 = "INSERT INTO ".cms_db_prefix()."module_compositions_journees (ref_action, idepreuve, journee, actif, statut) VALUES (?, ?, ?, ?, ?)";
	$dbresult1 = $db->Execute($query1, array($ref_action, $idepreuve, $journee, $actif, $statut));
	if($dbresult1)
	{
		$this->SetMessage('Composition ajoutée');		
	}
	else
	{
		$this->SetMessage('Une ereur est survenue : doublon ?');
	}
	
}
$this->RedirectToAdminTab('compos');
#
# EOF
#
?>