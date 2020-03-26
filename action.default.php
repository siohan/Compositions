<?php
if( !isset($gCms) ) exit;
//on vérifie si l'utilisateur est hablilité à être là (il doit être membre d'un groupe spécifique : capitaines)
//on instancie un compteur d'erreur
//debug_display($params, 'Parameters');
$error = 0;
$message = ''; //on instancie un message d'erreurs au cas où...
$comp_ops = new compositionsbis;
if(isset($params['genid']) && $params['genid'])
{
	$genid = $params['genid'];
	
	//on vérifie que le genid est bien capitaine
	
	$cap = $comp_ops->is_capitaine($genid);
	if (false == $cap)
	{
		$error++;
		$message.="Vous n'êtes pas autorisé à accéder à cette page.";
	}
	
}
else
{
	$error++;
	$message.=" Votre identifiant n'a pas été reconnu...";
}
if(isset($params['ref_action']) && $params['ref_action'])
{
	$ref_action = $params['ref_action'];
	$details_action = $comp_ops->details_ref_action($ref_action);
	$idepreuve = $details_action['idepreuve'];
	$date_limite = $details_action['date_limite'];
	$actif = $details_action['actif'];
	if($date_limite < time())
	{
		$error++;
		$message.=" La date limite de dépôt est dépassée !";
	}
	if($actif == 0)
	{
		$error++;
		$message.=" Cette page n'est plus active !";
	}
	$epreuve = $comp_ops->details_epreuve($idepreuve);
	$journee = $details_action['journee'];
	$smarty->assign('journee', $journee);
	
}
else
{
	$error++;
	$message.=" L'identifiant de l'action est inconnu...";
}
/*
if(isset($params['obj']) && $params['obj'])
{
	$obj = $params['obj'];
}
else
{
	$error++;
}
*/
if($error < 1)
{
	$details = $comp_ops->capitaine_of_what($genid, $idepreuve);
	$smarty->assign('friendlyname', $details['friendlyname']);
	//$smarty->assign('epreuve', $details_action['idepreuve']);
	$query = "SELECT ref_action, ref_equipe, genid, statut FROM ".cms_db_prefix()."module_compositions_compos_equipes WHERE ref_action = ? AND ref_equipe = ?";
	$dbresult = $db->Execute($query, array($ref_action, $details['equipe_id']));
	if($dbresult)
	{
		$rowclass = 'row1';
		if($dbresult->recordCount() >0)
		{
			$adh_ops =  new Asso_adherents;
			$eq_ops = new equipes_comp;
			$final_message = "";
			$complete = $eq_ops->is_complete($ref_action, $details['equipe_id']);
			$is_locked = $eq_ops->is_locked($ref_action, $details['equipe_id']);
			
			if(false == $complete)
			{
				$final_message.=" Ton équipe n'est pas complète...";
			}
			else
			{
				$final_message.=" Ton équipe est complète !!";
			}
			if(false == $is_locked)
			{
				$final_message.=" Tu n'as pas encore notifier le référent";
				$smarty->assign('locked', false);
			}
			else
			{
				$final_message.=" Le référent a été notifié.";
				$smarty->assign('locked', true);
			}
			$smarty->assign('final_message', $final_message);
			$onerow->rowclass=$rowclass;
			while($row = $dbresult->FetchRow())
			{
				$statut = $row['statut'];
				
				
				$smarty->assign('ref_action', $row['ref_action']);
				$smarty->assign('ref_equipe', $row['ref_equipe']);
				$smarty->assign('record_id', $genid);
				if($statut == "0")
				{
					//$this->CreateLink($id, 'fe_delete', $returnid, 'Verrouiller', array("obj"=>"fe_lock_unlock", "ref_action"=>$ref_action, "ref_equipe"=>$ref_equipe, "statut"=>"1")));
					$smarty->assign('modifier', $this->CreateLink($id, 'fe_add_edit_compos_equipe', $returnid, 'Modifier', array("ref_action"=>$ref_action, "ref_equipe"=>$row['ref_equipe'],"edit"=>"1")));
				}
				$smarty->assign('statut', $statut);
				$onerow = new StdClass;
				$onerow->ref_action = $row['ref_action'];
				$onerow->ref_equipe = $row['ref_equipe'];
				$onerow->licence = $adh_ops->get_name($row['genid']);
				$onerow->statut = $statut;
				$rowarray[] = $onerow;

			}

			$smarty->assign('itemcount', count($rowarray));
			$smarty->assign('items', $rowarray);
		
			echo $this->ProcessTemplate('fe_view_equipes.tpl');
		}
		else
		{
			//il n'y a pas de compo, on affiche une redirection
			$this->Redirect($id, 'fe_add_edit_compos_equipe', $returnid, array('ref_action'=>$ref_action, 'ref_equipe'=>$details['equipe_id'], 'record_id'=>$genid));
		}
	}
	else
	{
	// la requête n' a pas fonctionné, il y a une erreur
	}
}
else
{
	echo $message;
}
#
# EOF
#
?>