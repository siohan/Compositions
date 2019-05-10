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
if(isset($params['record_id']) && $params['record_id'] != "")
{
	$record_id = $params['record_id'];
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
	$licences = $comp_ops->capitaines();
	var_dump($licences);
	
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
	$query = "SELECT licence, date_debut, date_fin, motif FROM  ".cms_db_prefix()."module_compositions_absences WHERE id = ?";
	$dbresult = $db->Execute($query, array($record_id));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			
			$joueur = $ping_ops->get_name($row['joueur']);
			$date_debut = $row['date_debut'];
			$date_debut = $row['date_debut'];
			$motif = $row['motif'];
		}
	}
	$smarty->assign('joueur', $joueur);
	$smarty->assign('date_debut', $date_debut);
	$smarty->assign('date_fin', $date_fin);
	$smarty->assign('motif', $motif);
	
	
	
}



//var_dump($adresses);

$from = $this->GetPreference('admin_email');
$sujet = "Nouvelle absence";//$this->GetPreference('sujet_relance_email');		
$message = $this->GetTemplate('relance_email');
//$body = $this->ProcessTemplateFromData($message);
$body = 'Nouvelle absence';
$priority = 3;
if(is_array($adresses) && count($adresses) >1)
{
	//$destinataires  = implode(',',$adresses);
	foreach($adresses as $item=>$v)
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