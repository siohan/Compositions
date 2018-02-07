<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Use Commandes'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
if(isset($params['submit']))
{
	//on sauvegarde ! Ben ouais !
	$this->SetPreference('admin_email', $params['admin_email']);
	$this->SetPreference('sujet_relance_email', $params['sujet_relance_email']);
	$this->SetTemplate('relance_email', $params['relance_email']);
//	$this->SetPreference('new_command_subject', $params['newcommandsubject']);
//	$this->SetTemplate('newcommandemail_Sample',$params['newcommand_mail_template']);
	//on redirige !
	$this->RedirectToAdminTab('notifications');
}
$smarty->assign('start_form', 
		$this->CreateFormStart($id, 'admin_emails_tab', $returnid));
$smarty->assign('end_form', $this->CreateFormEnd ());
$smarty->assign('sujet_relance_email', $this->CreateInputText($id, 'sujet_relance_email',$this->GetPreference('sujet_relance_email'), 50, 150));
$smarty->assign('admin_email', $this->CreateInputText($id, 'admin_email',$this->GetPreference('admin_email'), 50, 150));
$smarty->assign('relance_email', $this->CreateSyntaxArea($id, $this->GetTemplate('relance_email'), 'relance_email', '', '', '', '', 80, 7));
$smarty->assign('submit', $this->CreateInputSubmit ($id, 'submit', $this->Lang('submit')));
echo $this->ProcessTemplate('emailings.tpl');
#
# EOF
#
?>