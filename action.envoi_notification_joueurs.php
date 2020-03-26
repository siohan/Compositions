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
debug_display($params, 'Parameters');
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
$mess_inst = cms_utils::get_module('Messages');
if(is_object($mess_inst)) $result = 1; //le module Messages est bien installé
if($error == 0)
{
	$comp_ops = new compositionsbis;
	//$ping_ops = new ping_admin_ops;
	//tt va bien on a les parametres requis
	//on va d'abord chercher les genid
	$genids = $comp_ops->licences_by_ref_equipe($ref_action, $ref_equipe);
	//var_dump($licences);
	$adresses = array();
	//on va chercher les emails pour les licences
	
	//on récupére d'autres infos
	$query = "SELECT journee, idepreuve FROM  ".cms_db_prefix()."module_compositions_journees WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			$journee = $row['journee'];
			$epreuve = $comp_ops->nom_compet($row['idepreuve']);
		}
	}
	
	
	$results = $comp_ops->get_equipe($ref_equipe);
	//var_dump($results);
	
	$friendlyname = $results['friendlyname'];
	$libequipe = $results['libequipe'];
	
	//var_dump($adresses);
	// on commence le traitement

	$from = $this->GetPreference('admin_email');
	$sujet = $this->GetPreference('sujet_relance_email');	
	$montpl = $this->GetTemplateResource('orig_relance_email.tpl');						
	$smarty = cmsms()->GetSmarty();	
	
	
	$priority = 3;
	$ar = 0;
	$relance = 0;
	$occurence = 0;
	$timbre = time();
	

	$senddate = date('Y-m-d');
	$sendtime = date("H:i:s");
	$replyto = $from;
	$sent = 1;
	//$gp_ops = new groups;
	//$recipients_number = $gp_ops->count_users_in_group($group_id);
	$recipients_number = 4;
	$group_id = 0;
	/*
	if(true == $result)//le module message est bien installé et actif
	{
		$mess_ops = new T2t_messages;
		$message_envoi = $mess_ops->add_message($from, $senddate, $sendtime, $replyto, $group_id,$recipients_number, $sujet, $body, $sent, $priority,$timbre,$ar,$relance, $occurence );
		$message_id =$db->Insert_ID();
	}
	*/
	//var_dump($message_id);
  	if(FALSE !== $genids)
	{
		$adh = cms_utils::get_module('Adherents');
		$adh_ops = new contact;
		$cg_ops = new CGExtensions;
		$retourid = $this->GetPreference('pageid_compositions');
		$page = $cg_ops->resolve_alias_or_id($retourid);
		
		foreach($genids as $tab)
		{
			$emails = $adh_ops->email_address($tab);
			//on vérifie que les licences renvoient bien une adresse email !
			if(FALSE !== $emails)
			{
				$sent = 0;
				$tpl = $smarty->createTemplate($montpl);
				$tpl->assign('epreuve', $epreuve);				
				$tpl->assign('journee', $journee);
				$tpl->assign('friendlyname', $friendlyname);
				$tpl->assign('libequipe', $libequipe);
			
			 	$output = $tpl->fetch();

				$cmsmailer = new \cms_mailer();
				$cmsmailer->reset();
				$cmsmailer->SetFrom($from);//$this->GetPreference('admin_email'));
				$cmsmailer->AddAddress($emails);
				$cmsmailer->IsHTML(true);
				$cmsmailer->SetPriority($priority);
				$cmsmailer->SetBody($output);
				$cmsmailer->SetSubject($sujet);
				
				
				if( !$cmsmailer->Send() ) 
				{
			            	audit('',$this->GetName(),'Problem sending email to '.$item);
					$sent = 0;
					$ar = 0;

			        }
				else
				{
					$sent = 1;
					$ar = 0;

				}
			}
			else
			{
				
			}
			$ar = 0;
			//$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $tab, $emails,$sent,$status, $ar);
		}

	}
	//var_dump($adresses);
	
	
}
/*
else
{
	
}




//on extrait les utilisateurs (genid) du groupe sélectionné
$contacts_ops = new contact;
//$adherents = $contacts_ops->UsersFromGroup($group_id);
	
	

if(is_array($adresses))
{
	if(count($adresses) >1)

	{
		//$destinataires  = implode(',',$adresses);
		foreach($adresses as $item=>$v)
		{

		//var_dump($item);

			$cmsmailer = new \cms_mailer();
			$cmsmailer->reset();
			//$cmsmailer->SetFrom($from);//$this->GetPreference('admin_email'));
			$cmsmailer->AddAddress($v,$name='');
			$cmsmailer->IsHTML(true);
			$cmsmailer->SetPriority($priority);
			$cmsmailer->SetBody($body);
			$cmsmailer->SetSubject($sujet);
			$cmsmailer->Send();
		
		
		
		        if( !$cmsmailer->Send() ) 
			{
		            	audit('',$this->GetName(),'Problem sending email to '.$item);
				$sent = 0;
				$ar = 0;

		        }
			else
			{
				$sent = 1;
				$ar = 0;
			
			}
			if(true == $result)
			{
				$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $v,$sent,$status, $ar);
			}	
		}
	}
	elseif(count($adresses) == 1)
	{
		$destinataires = $adresses[0];
		$cmsmailer = new \cms_mailer();
		$cmsmailer->reset();
	//	$cmsmailer->SetFrom($from);//$this->GetPreference('admin_email'));
		$cmsmailer->AddAddress($destinataires,$name='');
		$cmsmailer->IsHTML(true);
		$cmsmailer->SetPriority($priority);
		$cmsmailer->SetBody($body);
		$cmsmailer->SetSubject($sujet);
		$cmsmailer->Send();

	        if( !$cmsmailer->Send() ) 
		{
	            	$sent = 0;
			$ar = 0;
			audit('',$this->GetName(),'Problem sending email to '.$item);
		}
		else
		{
			$sent = 1;
			$ar = 0;

		}
		if(true == $result)
		{
			$add_to_recipients = $mess_ops->add_messages_to_recipients($message_id, $sels, $destinataires,$sent,$status, $ar);
		}
	}
	else
	{
		//pas d'emails envoyés aucune adresse valide !!
	}
	$this->SetMessage('email(s) envoyé(s)');
}
*/

$this->Redirect($id, 'defaultadmin', $returnid);


?>