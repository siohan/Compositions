<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################
//debug_display($params, 'Parameters');
if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//$smarty->assign ('raz', 
//$this->CreateLink($id, 'cotisations', $returnid, $this->Lang('raz')));

$smarty->assign('formstart',
		$this->CreateFormStart( $id, 'cotisations', $returnid ) );
	
		$smarty->assign('raz',
				$this->CreateInputSubmit($id, 'submit', $this->Lang('raz'), 'class="button"'));
		$smarty->assign('obj',
						$this->CreateInputHidden($id, 'obj', 'raz' ));
		$smarty->assign('cancel',
				$this->CreateInputSubmit($id,'cancel',
							$this->Lang('cancel')));
		
		$smarty->assign('formend',
				$this->CreateFormEnd());
	echo $this->ProcessTemplate('raz.tpl');


#
#EOF
#
?>