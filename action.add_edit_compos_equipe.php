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
	$phase = $comp_ops->get_phase($ref_action);
}
else
{
	$error++;
}
if(isset($params['ref_equipe']) && $params['ref_equipe'] != '')
{
	$ref_equipe = $params['ref_equipe'];
	//des restrictions pour cette équipe ? Clt mini ?etc...
	//$comp_ops = new Compositionsbis;
	//$restrictions = $comp_ops->restrictions($ref_equipe);
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
	$nb_player = $comp_ops->player_by_idepreuve($idepreuve,$phase);//le nb de joueurs nécessaires aux compos
	$nb_already_used = $comp_ops->nb_already_used_licences($ref_action);
	$mess = 'Vous avez utilisé '.$nb_already_used.'joueurs(euses) sur '.$nb_player.' nécessaires !';
	$smarty->assign('message', $mess);//echo $nb_already_used.'/'.$nb_player;
	//on créé un lien de retour
	$smarty->assign('retour', $this->CreateLink($id, 'view_compos',$returnid, '<= Retour', array("ref_action"=>$ref_action)));//on récupère les licences déjà utilisées s'il y en a
	$nb = 0;//on instancie une variable pour limiter les licences 
	$licences = $comp_ops->licences_disponibles($ref_action, $ref_equipe);
	//var_dump($licences);
	if (FALSE !== $licences)
	{
		$nb = count($licences);
		$licens = implode(',',$licences);
	}
	
	
	$query = "SELECT j.licence, CONCAT_WS(' ',j.nom, j.prenom ) AS joueur ,j.points, j.sexe, j.cat FROM ".cms_db_prefix()."module_adherents_adherents AS j, ".cms_db_prefix()."module_compositions_listes_joueurs as list WHERE list.licence = j.licence AND j.fftt = 1 AND j.actif = 1";//" ORDER BY j.nom ASC ";
	//des restrictions ?
	$clt = $comp_ops->restrictions_clt_mini($ref_equipe);
	if(FALSE !== $clt)
	{
		$query.= " AND j.points >= $clt";
	}
	if($nb >0)
	{
		$query.=" AND j.licence NOT IN ($licens)";
	}
	$query.=" ORDER BY j.points DESC";
	//echo $query;
	$dbresult = $db->Execute($query);

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

				$licence = $row['licence'];
				$joueur = $row['joueur'].'   ('.$row['points'].' pts/ '.$row['sexe'].' / '.$row['cat'].')';
				$rowarray[$licence]['name'] = $joueur;
				$rowarray[$licence]['participe'] = false;

				//on va chercher si le joueur est déjà dans la table
				$query2 = "SELECT licence, ref_action, ref_equipe FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE licence = ? AND ref_action = ? AND ref_equipe = ?";
				//echo $query2;
				$dbresultat = $db->Execute($query2, array($licence, $ref_action, $ref_equipe));

				if($dbresultat->RecordCount()>0)
				{
					while($row2 = $dbresultat->FetchRow())
					{


						$rowarray[$licence]['participe'] = true;
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
}

?>