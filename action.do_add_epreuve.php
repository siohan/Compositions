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
$comp_ops = new compositionsbis;
$error = 0;
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
	$record_id = $params['record_id'];
}

if(isset($params['libelle']))
{
	$libelle = $params['libelle'];
}
if(isset($params['description']))
{
	$description = $params['description'];
}
$actif = '';
if(isset($params['actif']) && $params['actif'] != '')
{
	$actif = $params['actif'];
}

if($edit == 0 && $error < 1)
{
	//on fait d'abord l'insertion 
	$add_epreuve = $comp_ops->add_epreuve($libelle, $description, $actif);
}
elseif($edit == 1 && $error < 1)
{
	//il s'agit d'une mise à jour !
	$update_epreuve = $comp_ops->update_epreuve($record_id, $libelle, $description, $actif);
}			
	$this->SetMessage($designation);
	$this->RedirectToAdminTab('compos');



#
# EOF
#
?>