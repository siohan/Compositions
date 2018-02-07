<?php
   if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Compositions use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
//	debug_display($params, 'Parameters');

echo $this->StartTabheaders();
if (FALSE == empty($params['activetab']) )
  {
    $tab = $params['activetab'];
  } else {
  $tab = 'compositions';
 }	
	echo $this->SetTabHeader('compositions', 'Compositions', ('compositions' == $tab)?true:false);
	echo $this->SetTabHeader('equipes', 'Equipes', ('equipes' == $tab)?true:false);
	echo $this->SetTabHeader('brulage', 'Brûlage' , ('brulage' == $tab)?true:false);
	echo $this->SetTabHeader('email', 'Emails' , ('email' == $tab)?true:false);



echo $this->EndTabHeaders();

echo $this->StartTabContent();
	
	
	echo $this->StartTab('compositions', $params);
    	include(dirname(__FILE__).'/action.admin_compositions_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('equipes', $params);
    	include(dirname(__FILE__).'/action.admin_equipes_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('brulage', $params);
    	include(dirname(__FILE__).'/action.admin_brulage_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('email', $params);
    	include(dirname(__FILE__).'/action.admin_emails_tab.php');
   	echo $this->EndTab();




echo $this->EndTabContent();
//on a refermé les onglets
?>