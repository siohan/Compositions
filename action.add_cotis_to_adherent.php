<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

$designation = '';
$licence = '';
$rowarray = array();
if(isset($params['cancel']))
{
	$this->redirectToAdminTab('adherents');
}
	
if(!isset($params['genid']) || $params['genid'] == '')
{
	$this->SetMessage("parametres manquants");
	$this->RedirectToAdminTab('groups');
}
else
{
	$genid = $params['genid'];
}
	
$db = $this->GetDb();
$adh_ops = new Asso_adherents;
$cotis_ops = new cotisationsbis;
$gp_ops = new groups;
$details_groups	= $gp_ops->member_of_groups($genid);
$liste_groups = $gp_ops->liste_groupes_dropdown();

$tpl = $smarty->CreateTemplate($this->GetTemplateResource('view_cotiz_groups.tpl'), null, null, $smarty);
$i = 0;//le compteur

$autorisation = $this->CheckPermission('Cotisations use');


	
	$query = "SELECT id, nom FROM ".cms_db_prefix()."module_cotisations_types_cotisations WHERE actif = 1";
	$dbresult = $db->Execute($query);
	if($dbresult && $dbresult->RecordCount() >0)
	{
		$pay_ops = new paiementsbis;
		$tpl->assign('liste_groups', $liste_groups);
		$tpl->assign('genid', $genid);

		while($row = $dbresult->FetchRow())
		{
			$i++;
			$tpl->assign('nom_gp_'.$i, $row['nom']);
			$tpl->assign('id_group_'.$i, $row['id']);
			$participe = $cotis_ops->belongs_exists($genid, $row['id']);
			if(true == $participe)
			{
				$tpl->assign('check_'.$i, true);
			}
			$masque = 'Cotiz_'.$genid.'_'.$row['id'];
			$reglement = $cotis_ops->is_cotis_paid($masque);
			
			if(true == $reglement)
			{
				$tpl->assign('unremovable_'.$i, true);
			}

		}
	}		

$tpl->assign('compteur', $i);
$tpl->display();
/*

//$paie_ops = new paiementsbis;
$mes_cotis = $cotis_ops->cotis_per_user($genid);
var_dump($mes_cotis);
if(!false == $mes_cotis)
{
	$nb = count($mes_cotis);
}
else
{
	$nb = 0;
}

if($nb >0 && is_array($mes_cotis))
{
	$tab = implode(', ', $mes_cotis);
	$query = "SELECT id, nom, description, tarif, actif FROM ".cms_db_prefix()."module_cotisations_types_cotisations AS j WHERE actif = 1 AND id NOT IN ($tab) "; 
}
else
{
	$query = "SELECT id, nom, description, tarif, actif FROM ".cms_db_prefix()."module_cotisations_types_cotisations AS j WHERE actif = 1";//" AND id  IN ($tab2)  ";
	//echo $query;
}
	//on montre un formulaire pour ajouter des cotisations
	
	$dbresult = $db->Execute($query);//, array($tableau));

		if(!$dbresult)
		{
			$designation.= $db->ErrorMsg();
			$this->SetMessage("$designation");
			$this->RedirectToAdminTab('groups');
		}

		$smarty->assign('formstart',
				$this->CreateFormStart( $id, 'do_assign_cotis_to_user', $returnid ) );
		$smarty->assign('licence1',
				$this->CreateInputHidden($id,'licence1',$genid));	
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				//var_dump($row);
				$onerow = new StdClass();
				$belongs = $this->belongs_exists( $row['id'], $genid);
				
					$onerow->id_cotisation = $row['id'];
					$onerow->nom = $row['nom'];
					$onerow->participe = $belongs;
			
				
				
				$rowarray[] = $onerow;
			}
			$smarty->assign('items',$rowarray);	

		}
		$smarty->assign('submit',
				$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
		$smarty->assign('cancel',
				$this->CreateInputSubmit($id,'cancel',
							$this->Lang('cancel')));
		$smarty->assign('back',
				$this->CreateInputSubmit($id,'back',
							$this->Lang('back')));

		$smarty->assign('formend',
				$this->CreateFormEnd());
	echo $this->ProcessTemplate('assign_user_cotis.tpl');

*/
#
#EOF
#
?>