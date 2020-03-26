<?php

if(!isset($gCms)) exit;
//on vérifie les permissions

$admin_email = $this->GetPreference('admin_email');
$comp_ops = new compositionsbis;
$db = cmsms()->GetDb();
global $themeObject;
debug_display($params, 'Parameters');
$aujourdhui = date('Y-m-d');
$error = 0;
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
if(isset($params['ref_equipe']) && $params['ref_equipe'] != "")
{
	$ref_equipe = $params['ref_equipe'];
}
else
{
	$error++;
}
if(isset($params['genid']) && $params['genid'] != "")
{
	$genid = $params['genid'];
}
else
{
	$error++;
}
//$mess_inst = cms_utils::get_module('Messages');
//if(is_object($mess_inst)) $result = 1;
if($error == 0)// && true == $result)
{
	
				//on verrouille la compo
				$comp_ops->lock_equipe($ref_action, $ref_equipe);
				$message = "L'équipe ".$ref_equipe." a terminé sa compo pour la journée ".$journee;
				
				$cmsmailer = new \cms_mailer();
				$subject = 'Une compo a été déposée';
			//	$cmsmailer->SetSMTPDebug($flag = TRUE);
				$cmsmailer->AddAddress($admin_email, $name='');
				$cmsmailer->AddBCC('claude.siohan@gmail.com', $name='Claude SIOHAN');
				$cmsmailer->IsHTML(true);
				$cmsmailer->SetPriority('3');
				$cmsmailer->SetBody($message);
				$cmsmailer->SetSubject($subject);


		                if( !$cmsmailer->Send() ) 
				{			
		                    	//return false;
				//	$mess_ops->not_sent_emails($message_id, $recipients);
		                }
	
	$this->Redirect($id, 'default', $returnid, array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe, "genid"=>$genid));
}





?>