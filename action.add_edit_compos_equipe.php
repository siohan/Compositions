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

//debug_display($params, 'Parameters');
$db =& $this->GetDb();
$comp_ops = new compositionsbis;
$adh_ops = new Asso_adherents;
$error = 0;
$edit = 0;//on edite donc on refait l'équipe, on choisit les licences non verrouillées
// edit = 1, on sélectionne les joueurs de cette équipe
if(isset($params['edit']) && $params['edit'] != '')
{
	$edit = $params['edit'];
}
//$edit = 0; //par défaut, pour savoir si on édite une compo existante ou s'il s'agit d'une nouvelle
//il faut les parametres suivants : idepreuve, saison, ref_action, et peut-être ref_action

if(isset($params['ref_action']) && $params['ref_action'] != '')
{
	$ref_action = $params['ref_action'];
	$idepreuve = $comp_ops->get_idepreuve($ref_action);
//	$phase = $comp_ops->get_phase($ref_action);
}
else
{
	$error++;
}
if(isset($params['ref_equipe']) && $params['ref_equipe'] != '')
{
	$ref_equipe = $params['ref_equipe'];
	//quelle est le groupe associé à cette équipe ?
	$eq_comp = new equipes_comp;
	$details = $eq_comp->details_equipe($ref_equipe);
	$liste_id = $details['liste_id'];
	if(is_null($liste_id) == true)
	{
		$liste_id = 1;
	}
	//var_dump($liste_id);
}
else
{
	$error++;
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
	$mess = 'Vous avez utilisé '.$nb_already_used.'joueurs(euses) sur '.$nb_player.' nécessaires !';
	$smarty->assign('message', $mess);//echo $nb_already_used.'/'.$nb_player;
	//on créé un lien de retour
	$smarty->assign('retour', $this->CreateLink($id, 'view_compos',$returnid, '<= Retour', array("ref_action"=>$ref_action)));//on récupère les licences déjà utilisées s'il y en a
	$nb = 0;//on instancie une variable pour limiter les licences 
	$licences = $comp_ops->licences_disponibles($ref_action, $ref_equipe);
	$already_used = $comp_ops->already_used_licences($ref_action);
	//var_dump($licences);
	if (FALSE !== $licences)
	{
		$nb = count($licences);
		$licens = implode(',',$licences);
	}
	//on vérifie qu'une liste de joueurs est déjà saisie pour cette épreuve
	//sinon on affiche tout le monde
	//$list = $comp_ops->liste_exists($idepreuve);
	//var_dump($list);
	
	$query = "SELECT j.genid, CONCAT_WS(' ',j.nom, j.prenom ) AS joueur, j.sexe, j.cat FROM ".cms_db_prefix()."module_adherents_adherents AS j, ".cms_db_prefix()."module_adherents_groupes_belongs as be WHERE be.genid = j.genid  AND be.id_group = ? AND j.actif = 1";

	
	if($nb >0)
	{
		$query.=" AND j.genid NOT IN ($licens)";
	}
	
	$query.=" ORDER BY joueur ASC";
	//echo $query;
	
		$dbresult = $db->Execute($query, array($liste_id));


		if(!$dbresult)
		{
			$designation.= $db->ErrorMsg();
			$this->SetMessage("$designation");
			$this->RedirectToAdminTab('compos');
		}

		$smarty->assign('formstart',
				$this->CreateFormStart( $id, 'do_add_edit_compos_equipes', $returnid ) );
		$smarty->assign('ref_action',
				$this->CreateInputHidden($id,'ref_action',$ref_action));
		$smarty->assign('ref_equipe',
				$this->CreateInputHidden($id,'ref_equipe',$ref_equipe));
		if($dbresult && $dbresult->RecordCount()>0)
		{
			while($row = $dbresult->FetchRow())
			{
				//var_dump($row);

				$genid = $row['genid'];
				$joueur = $row['joueur'].'   ('.$row['sexe'].' / '.$row['cat'].')';
				$rowarray[$genid]['name'] = $joueur;
				$rowarray[$genid]['participe'] = false;

				//on va chercher si le joueur est déjà dans la table
				$query2 = "SELECT genid, ref_action, ref_equipe FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE genid = ? AND ref_action = ? AND ref_equipe = ?";
				//echo $query2;
				$dbresultat = $db->Execute($query2, array($genid, $ref_action, $ref_equipe));

				if($dbresultat->RecordCount()>0)
				{
					while($row2 = $dbresultat->FetchRow())
					{


						$rowarray[$genid]['participe'] = true;
					}
				}
				//print_r($rowarray);





			}
			$smarty->assign('rowarray',$rowarray);	

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
				
	echo $this->ProcessTemplate('compos_equipes.tpl');
	
	
				
	//une créé un tableau avec les licences déjà utilisées
	$query = "SELECT genid FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe != ?";
	$dbresult = $db->Execute($query, array($ref_action, $ref_equipe));
	$rowarray2 = array();
	$rowclass='row1';
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$onerow= new StdClass();
			$onerow->rowclass= $rowclass;
			$onerow->joueur = $adh_ops->get_name($row['genid']);
			$onerow->delete_joueur = $this->CreateLink($id, 'delete', $returnid, $contents='<= Remettre',array("obj"=>"compos_joueur","record_id"=>$row['genid'], "ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe));
			($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
			$rowarray2[]= $onerow;		
		}
		
	}
	$smarty->assign('items', $rowarray2);
	$smarty->assign('itemcount', count($rowarray2));
	echo $this->ProcessTemplate('brulage.tpl');
}

?>