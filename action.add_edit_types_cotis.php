<?php

if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}


//debug_display($params, 'Parameters');
$db =& $this->GetDb();
if(!empty($_POST))
{
	if(isset($_POST['cancel']))
	{
		//redir
		$this->RedirectToAdminTab('cotisations');
	}
	$aujourdhui = date('Y-m-d ');
	$error = 0;
	$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert



	if (isset($_POST['record_id']) && $_POST['record_id'] !='')
	{
		$record_id = $_POST['record_id'];
		$edit = 1;
	}		
	$nom = '';
	if (isset($_POST['nom']) && $_POST['nom'] !='')
	{
		$nom = $_POST['nom'];
	}
	else
	{
		$error++;
	}

	$description = '';
	if (isset($_POST['description']) && $_POST['description'] !='')
	{
		$description = $_POST['description'];
	}
	$tarif = '';
	if (isset($_POST['tarif']) && $_POST['tarif'] !='')
	{
		$tarif = $_POST['tarif'];
	}



	$actif = 0;
	if (isset($_POST['actif']) && $_POST['actif'] !='')
	{
		$actif = $_POST['actif'];
	}
	$groupe = 0;
	if (isset($_POST['groupe']) && $_POST['groupe'] !='')
	{
		$groupe = $_POST['groupe'];
	}



	//on calcule le nb d'erreur
	if($error>0)
	{
		$this->Setmessage('Parametres requis manquants !');
		$this->RedirectToAdminTab('types_cotis');
	}
	else // pas d'erreurs on continue
	{
		if($edit == 0)
		{
			$query = "INSERT INTO ".cms_db_prefix()."module_cotisations_types_cotisations (nom, description, tarif,actif, groupe) VALUES ( ?, ?, ?, ?, ?)";
			$dbresult = $db->Execute($query, array($nom, $description,$tarif, $actif, $groupe));
			$this->SetMessage('Type modifié ou ajouté');
		}
		else
		{
			$query = "UPDATE ".cms_db_prefix()."module_cotisations_types_cotisations SET nom = ?, description = ?, tarif = ?, actif = ?, groupe = ? WHERE id = ?";
			$dbresult = $db->Execute($query, array($nom, $description, $tarif,$actif,$groupe,$record_id));
			$this->SetMessage('Type modifié ou ajouté');
		}
		$this->RedirectToAdminTab('types_cotis');
	}
}	
else
{
	//s'agit-il d'une modif ou d'une créa ?
	$record_id = '';
	$index = 0;
	$libelle = '';
	$actif = 0;
	$edit = 0;
	$nom = '';
	$description = '';
	$tarif = '0.00';
	$groupe = 0;
	if(isset($params['record_id']) && $params['record_id'] !="")
	{
			$record_id = $params['record_id'];
			$edit = 1;//on est bien en trai d'éditer un enregistrement
			//ON VA CHERCHER l'enregistrement en question
			$query = "SELECT * FROM ".cms_db_prefix()."module_cotisations_types_cotisations WHERE id = ?";
			$dbresult = $db->Execute($query, array($record_id));
			$compt = 0;
			while ($dbresult && $row = $dbresult->FetchRow())
			{
				$compt++;
				$id = $row['id'];
				$nom = $row['nom'];
				$tarif = $row['tarif'];
				$description = $row['description'];
				$actif = $row['actif'];
				$groupe = $row['groupe'];

			}
	}
	$gp_ops = new groups;
	$groups = $gp_ops->liste_groupes_dropdown();


	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_types_cotis.tpl'), null, null, $smarty);
	$tpl->assign('record_id', $record_id);
	$tpl->assign('nom', $nom);
	$tpl->assign('description', $description);
	$tpl->assign('liste_groupes', $groups);
	$tpl->assign('tarif', $tarif);
	$tpl->assign('groupe', $groupe);
	$tpl->assign('actif', $actif);
	$tpl->display();
}

	

	

#
# EOF
#
?>
