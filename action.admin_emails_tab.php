<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($_POST, 'Parameters');
if(!empty($_POST))
{
	$message = '';
	//on sauvegarde ! Ben ouais !
	$this->SetPreference('sms_sender', $_POST['sms_sender']);
	$this->SetPreference('admin_email', $_POST['admin_email']);
	$this->SetPreference('sujet_relance_email', $_POST['sujet_relance_email']);
	$this->SetPreference('pageid_compositions', $_POST['pageid_compositions']);
	if($_POST['use_messages']  == 1)
	{
		$module = \cms_utils::get_module('Messages');
		if( is_object( $module ) )
		{
			$this->SetPreference('use_messages', $_POST['use_messages']);

		}
		else
		{
			$this->SetPreference('use_messages', '0');
			$message.=" Module Messages absent ou non activé !";
		}
	}
	else
	{
		$this->SetPreference('use_messages', '0');
	}
	
	$message.=" Configuration modifiée";
	$this->SetMessage($message);
	$this->RedirectToAdminTab('config');
}
else
{
	$tpl = $smarty->CreateTemplate($this->GetTemplateResource('emailings.tpl'), null, null, $smarty);
	$tpl->assign('sms_sender',$this->GetPreference('sms_sender'));
	$tpl->assign('sujet_relance_email',$this->GetPreference('sujet_relance_email'));
	$tpl->assign('admin_email', $this->GetPreference('admin_email'));
	$tpl->assign('pageid_compositions', $this->GetPreference('pageid_compositions'));
	$tpl->assign('use_messages', $this->GetPreference('use_messages'));
	$tpl->display();
}
#
# EOF
#
?>