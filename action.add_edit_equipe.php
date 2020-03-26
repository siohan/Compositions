<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$adh_ops = new Asso_adherents;
$gp_ops = new groups;
$comp_ops = new compositionsbis;
$eq_ops = new equipes_comp;
if(!empty($_POST))
{
	if( isset($_POST['cancel']))
	{
		$this->RedirectToAdminTab();
	}
	
	//debug_display($_POST, 'Parameters');
	if (isset($_POST['edit']) && $_POST['edit'] != '')
	{
		$edit = $_POST['edit'];
	}
	if (isset($_POST['record_id']) && $_POST['record_id'] != '')
	{
		$record_id = $_POST['record_id'];
	}
	if (isset($_POST['libequipe']) && $_POST['libequipe'] != '')
	{
		$libequipe = $_POST['libequipe'];
	}
	if (isset($_POST['friendlyname']) && $_POST['friendlyname'] != '')
	{
		$friendlyname = $_POST['friendlyname'];
	}
	if (isset($_POST['capitaine']) && $_POST['capitaine'] != '')
	{
		$capitaine = $_POST['capitaine'];
	}
	if (isset($_POST['nb_joueurs']) && $_POST['nb_joueurs'] != '')
	{
		$nb_joueurs = $_POST['nb_joueurs'];
	}
	if (isset($_POST['idepreuve']) && $_POST['idepreuve'] != '')
	{
		$idepreuve = $_POST['idepreuve'];
	}
	if (isset($_POST['liste_id']) && $_POST['liste_id'] != '')
	{
		$liste_id = $_POST['liste_id'];
	}

	
	
		if($edit ==0)
		{
			//on ajoute une nouvelle équipe
			
			$add_team = $eq_ops->add_team($libequipe, $friendlyname, $idepreuve, $capitaine, $nb_joueurs, $liste_id);
			if(true == $add_team)
			{
				$this->SetMessage('Equipe ajoutée avec succès');
			}
			else
			{
				$this->SetMessage('Equipe non ajoutée, une erreur est apparue');
			}
		}//on vire toutes les données de cette compo avant 
		else
		{
			$update = $eq_ops->update_team($libequipe, $friendlyname, $idepreuve, $capitaine, $nb_joueurs, $liste_id, $record_id);
			if(true == $update)
			{
				$this->SetMessage('Equipe modifiée avec succès');
			}
			else
			{
				$this->SetMessage('Equipe non modifiée, une erreur est apparue');
			}
		}
		$this->RedirectToAdminTab('equipes');
		
}
else
{
	//debug_display($params, 'Parameters');
	// valeurs par défaut
	$record_id = '';
	$libequipe = '';
	$friendlyname = '';
	$nb_joueurs = 0;
	$capitaine = 0;
	$idepreuve = 0;
	$liste_id = 1;
	$edit = 0;
	
	$liste_adherents = $adh_ops->liste_adherents();
	$liste_groupes = $gp_ops->liste_groupes_dropdown();
	$liste_epreuves = $comp_ops->liste_epreuves();

	if(isset($params['record_id']) && $params['record_id'] !="")
	{
		$record_id = $params['record_id'];
		$edit = 1;
		$details = $eq_ops->details_equipe($record_id);
		$libequipe = $details['libequipe'];
		$friendlyname = $details['friendlyname'];
		$nb_joueurs = $details['nb_joueurs'];
		$capitaine = $details['capitaine'];
		$idepreuve = $details['idepreuve'];
		$liste_id = $details['liste_id'];
	}

	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_equipe.tpl'), null, null, $smarty);
	$tpl->assign('record_id',$record_id);
	$tpl->assign('edit',$edit);
	$tpl->assign('liste_adherents',$liste_adherents);
	$tpl->assign('idepreuve',$idepreuve);
	$tpl->assign('liste_epreuves',$liste_epreuves);
	$tpl->assign('liste_groupes',$liste_groupes);
	$tpl->assign('libequipe',$libequipe);
	$tpl->assign('friendlyname',$friendlyname);
	$tpl->assign('idepreuve',$idepreuve);
	$tpl->assign('capitaine',$capitaine);
	$tpl->assign('liste_id',$liste_id);
	$tpl->assign('nb_joueurs',$nb_joueurs);
	$tpl->display();

		
}


#
# EOF
#
?>