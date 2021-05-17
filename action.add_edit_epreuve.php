<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$comp_ops = new compositionsbis;
if(!empty($_POST))
{
	debug_display($_POST, 'Parameters');
	$this->SetCurrentTab('epreuves');
	if(isset($_POST['edition']) && $_POST['edition'] != '')
	{
		$edit = $_POST['edition'];
	}
	else
	{
		$edit = 0;//il s'agit d'un ajout de commande
	}

	if(isset($_POST['record_id']) && $_POST['record_id'] != '')
	{
		$record_id = $_POST['record_id'];
	}

	if(isset($_POST['libelle']))
	{
		$libelle = $_POST['libelle'];
	}
	if(isset($_POST['description']))
	{
		$description = $_POST['description'];
	}
	$actif = '1';
	if(isset($_POST['actif']) && $_POST['actif'] != '')
	{
		$actif = $_POST['actif'];
	}

	if($edit == 0)
	{
		//on fait d'abord l'insertion 
		$add_epreuve = $comp_ops->add_epreuve($libelle, $description, $actif);
		$this->SetMessage('Epreuve ajoutée !');
	}
	elseif($edit == 1)
	{
		//il s'agit d'une mise à jour !
		$update_epreuve = $comp_ops->update_epreuve($record_id, $libelle, $description, $actif);
		$this->SetMessage('Epreuve modifiée !');
	}			
		
		$this->RedirectToAdminTab();
	
}
else
{
	debug_display($params, 'Parameters');
	//valeur par défaut
	$liste_epreuves = $comp_ops->liste_epreuves();
	$actif = 1;
	$libelle = '';
	$description ='';
	$record_id = '';
	for($i=0; $i<=50;$i++)
	{
	//	echo $i;
		$liste_journees[$i] = $i;
	}
	
	$OuiNon = array("Inactif"=>"0", "Actif"=>"1");
	$edit = 0; //variable pour savoir s'il s'agit d'un ajout ou d'une modification
	if(isset($params['record_id']) && $params['record_id'] !="")
	{
			$record_id = $params['record_id'];
			$edit = 1;
			$details = $comp_ops->details_epreuve($record_id);
			$libelle = $details['libelle'];
			$description = $details['description'];
			$actif = $details['actif'];			
	}

	//on construit le formulaire
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_epreuve.tpl'), null, null, $smarty);
	$tpl->assign('record_id',$record_id);	
	$tpl->assign('edition',$edit);
	$tpl->assign('libelle',$libelle);
	$tpl->assign('description',$description);
	$tpl->assign('actif',$actif);
	$tpl->assign('liste_epreuves', $liste_epreuves);
	$tpl->display();					
		


		
}


#
# EOF
#
?>