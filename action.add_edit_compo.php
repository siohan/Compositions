<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$comp_ops = new compositionsbis;
//debug_display($_POST, 'Parameters');
global $themeObject;
if(!empty($_POST))
{
	if(isset($_POST['cancel']))
	{
		$this->RedirectToAdminTab();
	}
	
	$error = 0;
	$edit = 0;
	$date_created = date('Y-m-d');
	$statut = 0;
	if(isset($_POST['edit']) && $_POST['edit'] != "")
	{
		$edit = $_POST['edit'];
	}
	if(isset($_POST['record_id']) && $_POST['record_id'] != "")
	{
		$ref_action = $_POST['record_id'];
	}
	else
	{
		$error++;
	}
	if(isset($_POST['journee']) && $_POST['journee'] != "")
	{
		$journee = $_POST['journee'];
	}
	if(isset($_POST['idepreuve']) && $_POST['idepreuve'] != "")
	{
		$idepreuve = $_POST['idepreuve'];
	}	
	if(isset($_POST['actif']) && $_POST['actif'] != "")
	{
		$actif = $_POST['actif'];
	}
	if(isset($_POST['date_created']) && $_POST['date_created'] != "")
	{
		$date_created = $_POST['date_created'];
	}
	if(isset($_POST['statut']) && $_POST['statut'] != "")
	{
		$statut = $_POST['statut'];
	}
	$saison = '2020-2021';
	//traitement de la date
	$date_limite = mktime($_POST['limite_Hour'], $_POST['limite_Minute'], $_POST['limite_Second'],$_POST['limite_Month'], $_POST['limite_Day'], $_POST['limite_Year']);
	if($error < 1)
	{
		//tt va bien ! On continue...
		if($edit == 1)
		{
			//update
			$comp_ops->update_journee($ref_action, $idepreuve, $journee, $date_created, $actif, $statut, $date_limite);
		}
		else
		{
			// c'est un insert
			$comp_ops->add_journee($ref_action, $idepreuve, $journee, $date_created, $actif, $statut, $date_limite, $saison);
		}
		//on redirige
		$this->RedirectToAdminTab();
	}
}
else
{
	//debug_display($params, 'Parameters');
	//$ping = cms_utils::get_module('Ping');
	$ping_ops = new compositionsbis;
	$liste_epreuves_equipes = $ping_ops->liste_epreuves();
	//var_dump($liste_epreuves_equipes);
	for($i=1; $i<=50;$i++)
	{
	//	echo $i;
		$liste_journees[$i] = $i;
	}
	//var_dump($liste_journees); 
	$OuiNon = array("Inactif"=>"0", "Actif"=>"1");

	//valeurs par défaut
	$edit = 0; //variable pour savoir s'il s'agit d'un ajout ou d'une modification
	$journee = 0;
	$idepreuve = 0;
	$actif = 0;
	$record_id = $this->random_string(10);
	$date_created = date('Y-m-d');
	$statut = 0;
	$date_limite = time() + 7*24*3600;
	
	if(isset($params['record_id']) && $params['record_id'] !="")
	{
			$record_id = $params['record_id'];
			$edit = 1;
			$details_journee = $ping_ops->details_ref_action($record_id);
			$journee = $details_journee['journee'];
			$idepreuve = $details_journee['idepreuve'];
			$actif = $details_journee['actif'];
			$date_limite = $details_journee['date_limite'];
			$date_created = $details_journee['date_created'];
			$statut = $details_journee['statut'];
	}

	
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_compo.tpl'), null, null, $smarty);
	$tpl->assign('record_id', $record_id);
	$tpl->assign('edit', $edit);
	$tpl->assign('actif', $actif);
	$tpl->assign('idepreuve', $idepreuve);
	$tpl->assign('journee', $journee);
	$tpl->assign('date_limite', $date_limite);
	$tpl->assign('date_created', $date_created);
	$tpl->assign('statut', $statut);
	$tpl->assign('liste_epreuves_equipes', $liste_epreuves_equipes);
	$tpl->assign('liste_journees', $liste_journees);
	$tpl->display();
}


#
# EOF
#
?>