<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$this->SetCurrentTab('config');
//debug_display($params, 'Parameters');
//les valeurs par défaut :
$admin_email = 'root@localhost.com';
$use_messages = 1;
if(!empty($_POST))
{
	if(isset($_POST['cancel']))
	{
		$this->RedirectToAdminTab();
	}
	
	//on sauvegarde ! Ben ouais !
	$this->SetPreference('admin_email', $_POST['admin_email']);
	$this->SetPreference('use_messages', $_POST['use_messages']);
	
	$this->RedirectToAdminTab();
}
else
{
	//on affiche le formulaire
}
/*
$smarty->assign('start_form', 
		$this->CreateFormStart($id, 'admin_emails_tab', $returnid));
$smarty->assign('end_form', $this->CreateFormEnd ());
$smarty->assign('sujet_relance_email', $this->CreateInputText($id, 'sujet_relance_email',$this->GetPreference('sujet_relance_email'), 50, 150));
$smarty->assign('admin_email', $this->CreateInputText($id, 'admin_email',$this->GetPreference('admin_email'), 50, 150));
$smarty->assign('pageid_compositions', $this->CreateInputText($id, 'pageid_compositions',$this->GetPreference('pageid_compositions'), 50, 150));
$smarty->assign('relance_email', $this->CreateSyntaxArea($id, $this->GetTemplate('relance_email'), 'relance_email', '', '', '', '', 80, 7));
$smarty->assign('submit', $this->CreateInputSubmit ($id, 'submit', $this->Lang('submit')));
echo $this->ProcessTemplate('emailings.tpl');
*/
#
# EOF
#
?>