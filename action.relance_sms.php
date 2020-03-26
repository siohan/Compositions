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
debug_display($params, 'Parameters');
$aujourdhui = date('Y-m-d');
$error = 0;
include'bitly.php';
$admin_email = $this->GetPreference('admin_email');
//var_dump($admin_email);
if(isset($params['ref_action']) && $params['ref_action'] != "")
{
	$ref_action = $params['ref_action'];
	$details = $comp_ops->details_ref_action($ref_action);
	$journee = $details['journee'];
	$epreuve = $details['idepreuve'];
	
	//les paramètres bitly
	$sms = cms_utils::get_module('Sms');
	$client_id = $sms->GetPreference('bitly_client_id');
	$client_secret = $sms->GetPreference('bitly_client_secret');
	$user_access_token = $sms->GetPreference('bitly_access_token');
	
	//Les paramètres SMS
	$sender = $this->GetPreference('sms_sender');
//	$message = $this->GetTemplate('sms_relance');
	$message_reference = $this->random_string(15);
	$subtype = 'PREMIUM';
	$senddate = date('Y-m-d');
	$sendtime = date('H:i:s');
	$richsms_option = 0;
	$richsms_url = '';
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

//$mess_inst = cms_utils::get_module('Messages');
//if(is_object($mess_inst)) $result = 1;
if($error == 0)// && true == $result)
{
	$cg_ops = new CGExtensions;
	$cont_ops = new contact;
	$eq_ops = new equipes_comp;
//	$bit_ops = new bitly;
	$sms_ops = new sms_ops;
	$retourid = $this->GetPreference('pageid_compositions');
	$page = $cg_ops->resolve_alias_or_id($retourid);

	$query = "SELECT  idepreuve, capitaine, friendlyname,libequipe, id FROM  ".cms_db_prefix()."module_compositions_equipes WHERE idepreuve = ?";
	$dbresult = $db->Execute($query, array($idepreuve));
	if($dbresult && $dbresult->RecordCount()>0)
	{
		while($row = $dbresult->FetchRow())
		{
			
			$capitaine = $row['capitaine'];
			$epreuve = $row['idepreuve'];
			$friendlyname = $row['friendlyname'];
			$libequipe = $row['libequipe'];
			$equipe_id = $row['id'];
			$titre = $friendlyname.'('.$libequipe.') / Journée :'.$journee;
			
			//on vérifie si une compo est déjà complète
			//$complete = $eq_ops->is_complete($ref_action, $equipe_id);
			//var_dump($complete);
			$locked = $eq_ops->is_locked($ref_action, $equipe_id);
			 if(false == $locked)
			{
				//on vérifie que le capitaine a bien un numéro de portable 
				$mobile = $cont_ops->mobile($capitaine);
				if(!false == $mobile)
				{
					$lien = $this->create_url($id,'default',$page, array("ref_action"=>$ref_action, "genid"=>$capitaine));

					$params1 = array();
					$params1['access_token'] = $user_access_token;
					$params1['longUrl'] = urlencode($lien);
					var_dump($params1);
					
					$resultsok = bitly_get('shorten', $params1, $complex=true);
					$oklien = $resultsok['data']['url'];
					$smarty->assign('oklien', $oklien);
					$montpl = $this->GetTemplateResource('orig_smstemplate.tpl');
					//$montpl = $this->GetTemplate('sms_relance');
									
					$smarty = cmsms()->GetSmarty();
					// do not assign data to the global smarty
					$tpl = $smarty->createTemplate($montpl);
					$tpl->assign('oklien',$oklien);
					$tpl->assign('titre',$titre);
					$output = $tpl->fetch();
			                
					$sent = 0;
					$add_message = $sms_ops->add_message($message_reference,$subtype, $senddate, $sendtime,$sender, $output, $richsms_option,$richsms_url);
					if(true === $add_message)
					{
						$message_id = $db->Insert_ID();
						$add_to_recipients = $sms_ops->add_recipients($message_id, $id_envoi='0', $capitaine,$sent,$mobile);
					}

					//on construit le sms
					//on appelle la biblio smsenvoi
					
					$smsenv = new smsenvoi;
					
					
					if($smsenv->sendSMS($mobile,$output,'PREMIUM',$sender))
					{
							//ENVOI REUSSI
							$success=true;

							//Id de l'envoi effectué
							//Idéalement, cet id devrait être stocké en base de données
							$id_envoi=$smsenv->id;
							//on met la bdd à jour
						//	$maj_message = $sms_ops->maj_envoi($message_reference,$id_envoi);
							$maj_recipients = $sms_ops->maj_recipients($message_id, $id_envoi);									

					}
					
					


				}
			
			}
			
			
			
		}	
	}
	
	
}


$this->Redirect($id, 'defaultadmin', $returnid);


?>