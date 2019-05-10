<?php
   if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Compositions use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
//	debug_display($params, 'Parameters');

echo $this->StartTabheaders();
if (FALSE == empty($params['__activetab']) )
  {
    $tab = $params['__activetab'];
  } else {
  $tab = 'compositions';
 }	
	echo $this->SetTabHeader('epreuves', 'Epreuves', ('epreuves' == $tab)?true:false);
	echo $this->SetTabHeader('equipes', 'Equipes', ('equipes' == $tab)?true:false);
	echo $this->SetTabHeader('compositions', 'Compositions', ('compos' == $tab)?true:false);	
	echo $this->SetTabHeader('email', 'Emails' , ('email' == $tab)?true:false);



echo $this->EndTabHeaders();

echo $this->StartTabContent();
	
	
	
	echo $this->StartTab('epreuves', $params);
    	include(dirname(__FILE__).'/action.admin_epreuves_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('equipes', $params);
    	include(dirname(__FILE__).'/action.admin_equipes_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('compositions', $params);
    	include(dirname(__FILE__).'/action.admin_compositions_tab.php');
   	echo $this->EndTab();

	/**/
	echo $this->StartTab('email', $params);
    	include(dirname(__FILE__).'/action.admin_emails_tab.php');
   	echo $this->EndTab();




echo $this->EndTabContent();
//on a refermé les onglets
?>