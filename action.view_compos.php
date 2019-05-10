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
global $themeObject;
//debug_display($params, 'Parameters');
$db = cmsms()->GetDb();
$error = 0;
$comp_ops = new compositionsbis;

//il faut les parametres suivants : idepreuve, saison, ref_action, et peut-être ref_action
$smarty->assign('retour', $this->CreateLink($id, 'defaultadmin', $returnid, '<= Retour'));
if(isset($params['ref_action']) && $params['ref_action'] != '')
{
	$ref_action = $params['ref_action'];
}
else
{
	$error++;
}
if(isset($params['idepreuve']) && $params['idepreuve'] != '')
{
	$idepreuve = $params['idepreuve'];
}
else
{
	$idepreuve = $comp_ops->get_idepreuve($ref_action);
}
$epreuve = $comp_ops->nom_compet($idepreuve);
$smarty->assign('epreuve', $epreuve);
if(isset($params['ref_equipe']) && $params['ref_equipe'] != '')
{
	$ref_equipe1 = $params['ref_equipe'];
	$ref_equipe = $params['ref_equipe'];
}
else
{
	//pas d'équipe !
	//on en choisit une !
	$query = "SELECT id FROM ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ?  ORDER BY id ASC LIMIT 1";
	$dbresult = $db->Execute($query, array($idepreuve));
	if($dbresult && $dbresult->RecordCount() >0)
	{
		$row = $dbresult->FetchRow();
		$ref_equipe1 = $row['id'];
		$ref_equipe = $row['id'];
	}
	
}

if($error >0)
{
	$this->SetMessage('Paramètres manquants');
	$this->RedirectToAdminTab('compos');
}
else
{
	$nb_player = $comp_ops->player_by_idepreuve($idepreuve);//le nb de joueurs nécessaires aux compos
	$nb_already_used = $comp_ops->nb_already_used_licences($ref_action);
	$mess = 'Vous avez utilisé '.$nb_already_used.' joueurs(euses) sur '.$nb_player.' nécessaires !';
	$smarty->assign('message', $mess);
	//on récupère d'abord les équipes concernées
	$query = "SELECT id, libequipe, friendlyname FROM ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ? ORDER BY id ASC";
	$dbresult = $db->Execute($query, array($idepreuve));
	
	$rowarray= array();
	$rowclass = 'row1';
	if($dbresult && $dbresult->RecordCount()>0)
	{
			while($row = $dbresult->FetchRow())
			{
				$onerow = new StdClass;
				$onerow->rowclass=$rowclass;
				$libequipe = $row['libequipe'];
				$friendlyname = $row['friendlyname'];
				$ref_eq = $row['id'];
				if($ref_eq == $ref_equipe1)
				{
					$classe = 1;
				}
				else
				{
					$classe = 0;
				}
				$onerow->class= $classe;
				$lienequipe = (!empty($row['friendlyname'])?$row['friendlyname']: $row['libequipe']);
				$onerow->equipe = $this->CreateLink($id, 'view_compos', $returnid, $lienequipe, array("ref_action"=>$ref_action, "ref_equipe"=>$row['id']));
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
			}
			$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
			$smarty->assign('itemcount', count($rowarray));
			$smarty->assign('items', $rowarray);
			
		//	echo $this->ProcessTemplate('view_equipes.tpl');
	}		
	//on attaque la deuxième requete pour montrer les compos déjà saisies
	$query2 = "SELECT ref_action, ref_equipe, genid, statut FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
	$dbresult2 = $db->Execute($query2, array($ref_action, $ref_equipe1));
	$rowarray2 = array();

	if($dbresult2)
	{
		if($dbresult2->RecordCount()>0)
		{
			$adh_ops =  new Asso_adherents;
			$onerow = new StdClass;
			$lock = '<img src="../modules/Compositions/images/lock.png" class="systemicon" alt="Déverrouiller" title="Déverrouiller">';
			$unlock = '<img src="../modules/Compositions/images/unlock.png" class="systemicon" alt="Verrouiller" title="Verrouiller">';
			$onerow->rowclass=$rowclass;
			while($row2 = $dbresult2->FetchRow())
			{
				$statut = $row2['statut'];
				if($statut == "0")
				{
					$smarty->assign('lock', $this->CreateLink($id, 'lock', $returnid, $unlock, array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe, "lock"=>"1")));
					$relance = '<img src="../modules/Paiements/images/forward-email-16.png" class="systemicon" alt="Envoyer une relance" title="Envoyer une relance">';
					$smarty->assign('delete', $this->CreateLink($id, 'delete', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'), array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe)));
					$smarty->assign('modifier', $this->CreateLink($id, 'add_edit_compos_equipe', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe,"edit"=>"1")));
				}
				else
				{
					$smarty->assign('lock', $this->CreateLink($id, 'unlock', $returnid, $lock, array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe, "lock"=>"0")));
					$smarty->assign('emailing', $this->CreateLink($id, 'emailing', $returnid, 'Envoi notifications', array("ref_action"=>$ref_action,"ref_equipe"=>$ref_equipe1)));
				}
				$onerow2 = new StdClass;
				$onerow2->ref_action = $row2['ref_action'];
				$onerow2->ref_equipe = $row2['ref_equipe'];
				$onerow2->licence = $adh_ops->get_name($row2['genid']);
				$onerow2->statut = $statut;
				$rowarray2[] = $onerow2;

			}

			$smarty->assign('itemcount2', count($rowarray2));
			$smarty->assign('items2', $rowarray2);

		}
		else
		{
			$this->Redirect($id, 'add_edit_compos_equipe', $returnid, array('ref_action'=>$ref_action, 'ref_equipe'=>$ref_equipe));
		}
	} 
//	$lock = '<img src="../modules/Compositions/images/lock.png" class="systemicon" alt="Déverrouiller" title="Déverrouiller">';
//	$unlock = '<img src="../modules/Compositions/images/unlock.png" class="systemicon" alt="Verrouiller" title="Verrouiller">';
	
	$smarty->assign('ajouter', $this->CreateLink($id, 'add_edit_compos_equipe', $returnid, 'Ajouter une composition', array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe)));
	
	echo $this->ProcessTemplate('view_equipes.tpl');
}
