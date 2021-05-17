<?php

if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db =& $this->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$aujourdhui = date('Y-m-d');
//$ping = new Ping();
$act = 1;//par defaut on affiche les actifs (= 1 )
$shopping = '<img src="../modules/Adherents/images/shopping.jpg" class="systemicon" alt="Commandes" title="Commandes">';
$smarty->assign('add_users', 
		$this->CreateLink($id, 'edit_adherent',$returnid, 'Ajouter'));
$smarty->assign('shopping', $shopping);
$query = "SELECT * FROM ".cms_db_prefix()."module_cotisations_types_cotisations WHERE id_cotisation = ?";
if(isset($params['id_cotisation']) && $params['id_cotisation'] != '')
{
	$id_cotisation = $params['id_cotisation'];
	$req = 1;
	
}
$query1 = "SELECT nom FROM ".cms_db_prefix()."module_cotisations_types_cotisations WHERE id = ?";
$dbresultat = $db->Execute($query1, array($id_cotisation));
if($dbresultat && $dbresultat->RecordCount()>0)
{
	$row = $dbresultat->FetchRow();
	$nom = $row['nom'];
	$smarty->assign('nom', $nom);
}

$query2 = "SELECT adh.id, adh.licence, adh.genid, adh.nom, adh.prenom, adh.actif, be.id_cotisation, be.ref_action FROM ".cms_db_prefix()."module_adherents_adherents AS adh, ".cms_db_prefix()."module_cotisations_belongs AS be WHERE adh.genid = be.genid AND be.id_cotisation = ?";//" WHERE actif = 1";
$query2.=" ORDER BY nom ASC ";
$smarty->assign('act', $act);
	$dbresult = $db->Execute($query2,array($id_cotisation));

$rowarray = array();
$rowclass = 'row1';
$paiements_ops = new paiementsbis();
if($dbresult && $dbresult->RecordCount() >0)
{
	
	while($row = $dbresult->FetchRow())
	{
	
		$ref_action = $row['ref_action'];
		$onerow = new StdClass();
		$onerow->rowclass = $rowclass;
		$onerow->genid= $row['genid'];
		$onerow->nom= $row['nom'];
		$onerow->prenom= $row['prenom'];		
		$onerow->actif= $row['actif'];
		$onerow->id_cotisation= $row['id_cotisation'];
		$nb = $paiements_ops->is_paid($ref_action);

		if(TRUE == $nb)
		{
			$onerow->cotis_paid = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('true'), '', '', 'systemicon');
		}
		else
		{
			$onerow->cotis_paid = $this->CreateLink($id, 'cotisations', $returnid,$themeObject->DisplayImage('icons/system/false.gif', $this->Lang('false'), '', '', 'systemicon'), array("obj"=>"payment","ref_action"=>$row['ref_action'], "record_id"=>$id_cotisation));
			$onerow->delete = $this->CreateLink($id, 'cotisations',$returnid,$themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array("obj"=>"delete_belongs","ref_action"=>$row['ref_action']));
		}
		
		($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
		$rowarray[]= $onerow;
		
	}
	$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
	$smarty->assign('itemcount', count($rowarray));
	$smarty->assign('items', $rowarray);
}
elseif(!$dbresult)
{
	echo $db->ErrorMsg();
}

//$query.=" ORDER BY date_compet";
echo $this->ProcessTemplate('view_group_users.tpl');

?>