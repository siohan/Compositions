<?php

if(!isset($gCms)) exit;
$db =& $this->GetDb();

if(!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
debug_display($params, 'Parameters');
$error = 0; //on instancie un compteur d'erreurs


$obj = "journee";
if(isset($params['obj']) && $params['obj'] !='')
{
	$obj = $params['obj'];
}		
if($error <1)
{
	$comp_ops = new compositionsbis;

	switch($obj)
	{
		case "activate_epreuve":
			
			$record_id = $params['record_id'];
			if(isset($params['record_id']) && $params['record_id'] !='')
			{
				$del = $comp_ops->activate_epreuve($record_id);
				if(true == $del)
				{
					$this->SetMessage('Epreuve activée');
				}
				else
				{
					$this->SetMessage('Activation impossible, une erreur est apparue');
				}
				
				$this->RedirectToAdminTab('epreuves');
			}
		break;
		
		case "desactivate_epreuve":
			
			$this->SetCurrentTab('epreuves');
			if(isset($params['record_id']) && $params['record_id'] !='')
			{
				
				$record_id = $params['record_id'];
				$del = $comp_ops->desactivate_epreuve($record_id);
				if(true == $del)
				{
					$this->SetMessage('Epreuve désactivée');
				}
				else
				{
					$this->SetMessage('Désactivation impossible, une erreur est apparue');
				}
				
				
			}
			$this->RedirectToAdminTab();
		break;
		
		
	}

}

#EOF
#
?>