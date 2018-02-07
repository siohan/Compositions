<?php

if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$db = cmsms()->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$aujourdhui = date('Y-m-d');
$error = 0;
if(isset($params['ref_action']) && $params['ref_action'] != "")
{
	$ref_action = $params['ref_action'];
}
else
{
	$error++;
}
if(isset($params['ref_equipe']) && $params['ref_equipe'] != "")
{
	$ref_equipe = $params['ref_equipe'];
}
else
{
	$error++;
}
if($error == 0)
{
	$comp_ops = new compositionsbis;
	$ping_ops = new ping_admin_ops;
	//tt va bien on a les parametres requis
	//on va d'abord chercher les licences
	$licences = $comp_ops->licences_by_ref_equipe($ref_action, $ref_equipe);
	//var_dump($licences);
	
	//on va chercher les emails pour les licences
	if(FALSE !== $licences)
	{
		$adh = cms_utils::get_module('Adherents');
		$adh_ops = new contact;
		
		foreach($licences as $tab)
		{
			$emails = $adh_ops->email_address($tab);
			//on vérifie que les licences renvoient bien une adresse email !
			if(FALSE !== $emails)
			{
				$adresses[] = $emails;
			}
		}
	
	}
	//on récupére d'autres infos
	$query = "SELECT journee, idepreuve, phase FROM  ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$journee = $row['journee'];
			$epreuve = $ping_ops->nom_compet($row['idepreuve']);
			$phase = $row['phase'];
		}
	}
	$smarty->assign('journee', $journee);
	$smarty->assign('epreuve', $epreuve);
	$smarty->assign('phase', $phase);
	
	$results = $comp_ops->get_equipe($ref_equipe);
	//var_dump($results);
	
	$smarty->assign('friendlyname', $results['friendlyname']);
	$smarty->assign('libequipe', $results['libequipe']);
	
}



//var_dump($adresses);

$from = $this->GetPreference('admin_email');
$sujet = $this->GetPreference('sujet_relance_email');		
$message = $this->GetTemplate('relance_email');
$body = $this->ProcessTemplateFromData($message);
$priority = 3;
if(is_array($adresses) && count($adresses) >1)
{
	$destinataires  = implode(',',$adresses);
	foreach($destinataires as $item=>$v)
	{

	//var_dump($item);

		$cmsmailer = new \cms_mailer();
		$cmsmailer->reset();
		$cmsmailer->SetFrom($from);//$this->GetPreference('admin_email'));
		$cmsmailer->AddAddress($v,$name='');
		$cmsmailer->IsHTML(true);
		$cmsmailer->SetPriority($priority);
		$cmsmailer->SetBody($body);
		$cmsmailer->SetSubject($sujet);
		$cmsmailer->Send();
	        if( !$cmsmailer->Send() ) {
	            audit('',$this->GetName(),'Problem sending email to '.$item);

	        }
	}
	
}
else
{
	$destinataires = $adresses[0];
	$cmsmailer = new \cms_mailer();
	$cmsmailer->reset();
	$cmsmailer->SetFrom($from);//$this->GetPreference('admin_email'));
	$cmsmailer->AddAddress($destinataires,$name='');
	$cmsmailer->IsHTML(true);
	$cmsmailer->SetPriority($priority);
	$cmsmailer->SetBody($body);
	$cmsmailer->SetSubject($sujet);
	$cmsmailer->Send();
        if( !$cmsmailer->Send() ) {
            audit('',$this->GetName(),'Problem sending email to '.$item);
	}
}
$this->SetMessage('email(s) envoyé(s)');
$this->Redirect($id, 'defaultadmin', $returnid);


?>