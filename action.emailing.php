<?php

if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}

$comp_ops = new compositionsbis;
$db = cmsms()->GetDb();
global $themeObject;
//debug_display($params, 'Parameters');
$aujourdhui = date('Y-m-d');
$error = 0;

$admin_email = $this->GetPreference('admin_email');
$subject = $this->GetPreference('sujet_relance_email');
//var_dump($admin_email);
if(isset($params['ref_action']) && $params['ref_action'] != "")
{
	$ref_action = $params['ref_action'];
	$details = $comp_ops->details_ref_action($ref_action);
	$journee = $details['journee'];
	$epreuve = $details['idepreuve'];
}
else
{
	$error++;
}
if(isset($params['idepreuve']) && $params['idepreuve'] != "")
{
	$idepreuve = $params['idepreuve'];
}
else
{
	$error++;
}

//on insère individuellement
//$mess_inst = cms_utils::get_module('Messages');
//if(is_object($mess_inst)) $result = 1;
if($error == 0)// && true == $result)
{
	
	if(true == $this->GetPreference('use_messages'))
	{
		$mess_ops = new T2t_messages;

		$senddate = date('Y-m-d');
		$sendtime = date('H:i:s');
		$replyto = $admin_email;
		$group_id = 0;
		$recipients_number = 0;
		$sent = 1;
		$priority = 3;
		$timbre = time();
		$ar = 0;
		$relance = 0;
		$occurence = 0;
		$add = $mess_ops->add_message($admin_email, $senddate, $sendtime, $replyto, $group_id,$recipients_number, $subject, $subject, $sent, $priority, $timbre, $ar, $relance, $occurence);
		if(true == $add)
		{
			$last_id = $db->Insert_ID();
		}
	}
	
	$cg_ops = new CGExtensions;
	$cont_ops = new contact;
	$eq_ops = new equipes_comp;
	$retourid = $this->GetPreference('pageid_compositions');
	$page = $cg_ops->resolve_alias_or_id($retourid);

	$query = "SELECT  idepreuve, capitaine, friendlyname, id FROM  ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ?";
	$dbresult = $db->Execute($query, array($idepreuve));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			
			$capitaine = $row['capitaine'];
			$epreuve = $row['idepreuve'];
			$friendlyname = $row['friendlyname'];
			$equipe_id = $row['id'];
			
			//on vérifie si une compo est déjà complète
			//$complete = $eq_ops->is_complete($ref_action, $equipe_id);
			//var_dump($complete);
			$locked = $eq_ops->is_locked($ref_action, $equipe_id);
			 if(false == $locked)
			{
				//on vérifie que le capitaine a bien une adresse email 
				$adresse_email = $cont_ops->email_address($capitaine);
				if(!false == $adresse_email)
				{
					$lien = $this->create_url($id,'default',$page, array("ref_action"=>$ref_action, "genid"=>$capitaine));
					//$subject = 'Compose ton équipe';
					$priority = 3;
					$montpl = $this->GetTemplateResource('email_compos_equipes.tpl');						
					$smarty = cmsms()->GetSmarty();
					// do not assign data to the global smarty
					$tpl = $smarty->createTemplate($montpl);
					$tpl->assign('lien',$lien);
					$tpl->assign('epreuve',$epreuve);
					$tpl->assign('journee', $journee);
					$tpl->assign('friendlyname', $friendlyname);
				 	$output = $tpl->fetch();
				
					$i = 0; //Pour inclure un seul message originel
					if(true == $this->GetPreference('use_messages'))
					{
						$sent = 1;
						$status = 'Ok';
						$ar = 0;
						$send_to_recipients = $mess_ops->add_messages_to_recipients($last_id, $capitaine, $adresse_email,$output,$sent,$status, $ar);
						
					}
					$cmsmailer = new \cms_mailer();

					//$cmsmailer->SetSMTPDebug($flag = TRUE);
					$cmsmailer->SetFrom($admin_email);
					$cmsmailer->AddReplyTo( $admin_email, $name = '' );
					$cmsmailer->AddAddress($adresse_email, $name='');
				//	$cmsmailer->AddBCC('claude.siohan@gmail.com', $name='Claude SIOHAN');
					$cmsmailer->IsHTML(true);
					$cmsmailer->SetPriority($priority);
					$cmsmailer->SetBody($output);
					$cmsmailer->SetSubject($subject);


			                if( !$cmsmailer->Send() ) 
					{			
			                    	//return false;
						if(true == $this->GetPreference('use_messages'))
						{
							$mess_ops->not_sent_emails($message_id, $capitaine);
						}
			                }


				}
				else
				{
					//on indique l'erreur : pas d'email disponible !
					$senttouser = 0;
					$status = "Email absent";
					$ar = 0;
					$email_contact = "rien";
				}
			}
			
			
			
		}	
	}
	
	
}


$this->Redirect($id, 'defaultadmin', $returnid);


?>