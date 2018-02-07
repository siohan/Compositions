<?php
if (!isset($gCms)) exit;

if (!$this->CheckPermission('Compositions use'))
{
	$this->SetMessage($this->Lang('needpermission'));
	$this->RedirectToAdminTab('compos');
}
debug_display($params, 'parameters');
if(isset($params['cancel']) && $params['cancel'] == 'Annuler')
{
	$this->Redirect($id, 'defaultadmin', $returnid);
}
$error = 0;
if (isset($params['idepreuve']) && $params['idepreuve'] != '')
{
	$idepreuve = $params['idepreuve'];
}
else
{
	$error++;
}

		
if($error ==0)
{
	//on vire toutes les données de cette compo avant 
	$query = "DELETE FROM ".cms_db_prefix()."module_compositions_listes_joueurs WHERE idepreuve = ?";
	$dbquery = $db->Execute($query, array($idepreuve));
	
	//la requete a fonctionné ?
	
	if($dbquery)
	{
		$comp_ops = new compositionsbis;
		$brul_ops = new brulage;
		$licence = '';
		if (isset($params['licence']) && $params['licence'] != '')
		{
			$licence = $params['licence'];
			$error++;
		}
		foreach($licence as $key=>$value)
		{
			$query2 = "INSERT INTO ".cms_db_prefix()."module_compositions_listes_joueurs (idepreuve, licence) VALUES (?, ?)";
		//	echo $query2;
			$dbresultat = $db->Execute($query2, array($idepreuve,$key));
			/*
			//on insère aussi ds la tbl brulage ?
			if($dbresultat)
			{
				//on vérifie si le joueur est déjà présent ou non
				$present = $brul_ops->is_already_there($idepreuve,$key);
				//var_dump($present);
				if(FALSE === $present)
				{
					$add_player = $brul_ops->add_player($idepreuve, $key);
				}
			}
			*/
		}
	$this->SetMessage('participants ajoutés !');
	}
	else
	{
		echo "la requete de suppression est down !";
	}
		
		
}
else
{
	echo "Il y a des erreurs !";
}
		


$this->RedirectToAdminTab('compos');//($id, 'view_compos', $returnid, array("ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe));

?>