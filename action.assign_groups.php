<?php
if( !isset($gCms) ) exit;
####################################################################
##                                                                ##
####################################################################

$rowarray = array();

	
	if(!isset($params['genid']) || $params['genid'] == '')
	{
		$this->SetMessage("parametres manquants");
		$this->RedirectToAdminTab('groups');
	}
	else
	{
		$record_id = $params['genid'];
		$genid = $record_id;
	}

	
$db = $this->GetDb();
$query = "SELECT id, nom, description FROM ".cms_db_prefix()."module_adherents_groupes AS j WHERE actif = '1' AND nom != 'adherents' ORDER BY nom ASC ";
//echo $query;
$dbresult = $db->Execute($query);

	if(!$dbresult)
	{
		$designation.= $db->ErrorMsg();
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('groups');
	}

	$smarty->assign('formstart',
			$this->CreateFormStart( $id, 'do_assign_groups', $returnid ) );
	$smarty->assign('record_id',
			$this->CreateInputText($id,'record_id',$record_id,10,15));	
	if($dbresult && $dbresult->RecordCount()>0)
	{
		$gp_ops = new groups;
		while($row = $dbresult->FetchRow())
		{
			$onerow = new StdClass();
		//	$onerow->rowclass = $rowclass;
			$onerow->id_group = $row['id'];
			$onerow->nom = $row['nom'];
			$participe = $gp_ops->is_member($genid, $row['id']);
			if(true === $participe)
			{
				$onerow->participe = 1;
			}
			else
			{
				$onerow->participe = 0;
			}
			
			$rowarray[]= $onerow; 
			
		}
	
	}
//	var_dump($rowarray);
	$smarty->assign('items', $rowarray);
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));
	$smarty->assign('back',
			$this->CreateInputSubmit($id,'back',
						$this->Lang('back')));

	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('assign_groups.tpl');
#
#EOF
#
?>