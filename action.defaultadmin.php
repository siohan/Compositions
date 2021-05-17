<?php
   if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Compositions use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
//	debug_display($params, 'Parameters');

echo $this->StartTabheaders();
$active_tab = empty($params['active_tab']) ? '' : $params['active_tab'];

	echo $this->SetTabHeader('compos', 'Compositions', ($active_tab == 'compos')?true:false);
	echo $this->SetTabHeader('epreuves', 'Epreuves', ($active_tab == 'epreuves')?true:false);
	echo $this->SetTabHeader('equipes', 'Equipes', ($active_tab == 'equipes')?true:false);	
	echo $this->SetTabHeader('brulage', 'Brulage', ($active_tab == 'brulage')?true:false);		
	echo $this->SetTabHeader('config', 'Config' , ($active_tab == 'config')?true:false);



echo $this->EndTabHeaders();

echo $this->StartTabContent();
	
	
	
	echo $this->StartTab('compos', $params);
    	include(dirname(__FILE__).'/action.admin_compositions_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('epreuves', $params);
    	include(dirname(__FILE__).'/action.admin_epreuves_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('equipes', $params);
    	include(dirname(__FILE__).'/action.admin_equipes_tab.php');
   	echo $this->EndTab();	

	echo $this->StartTab('brulage', $params);
    	include(dirname(__FILE__).'/action.admin_brulage_tab.php');
   	echo $this->EndTab();
	/**/
	echo $this->StartTab('config', $params);
    	include(dirname(__FILE__).'/action.admin_emails_tab.php');
   	echo $this->EndTab();





echo $this->EndTabContent();
//on a refermé les onglets
?>