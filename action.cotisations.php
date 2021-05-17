<?php

if(!isset($gCms)) exit;
//on vérifie les permissions
if(!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
$db =& $this->GetDb();
global $themeObject;
$aujourdhui = date('Y-m-d');
$cotis_ops = new cotisationsbis;

$use_paiements = $this->GetPreference('use_paiements');
$exercice = $this->GetPreference('exercice');
if(true == $use_paiements)
{
	$paiements_ops = new paiementsbis;
}
if(isset($params['obj']) && $params['obj'] != '')
{
	$obj = $params['obj'];
}

switch($obj)
{
	case "delete_user_cotis":
		if(isset($params['ref_action']) && $params['ref_action'] != '')
		{
			$ref_action = $params['ref_action'];
			$delete_user_paiement = $cotis_ops->delete_user_paiement($ref_action);
			if(true === $delete_user_paiement)
			{
				$this->SetMessage('Cotisation supprimée. Versement(s) supprimé(s)');
				
			}
			else
			{
				$this->SetMessage('La suppression a échouée !');
			}
		}
		else
		{
			$this->SetMessage('Une erreur est survenue !');
		}
		$this->RedirectToAdminTab('adherents');//($id, 'defaultadmin', $returnid);
		
	break;
	
	
	case "delete_cotis" : 
		if(isset($params['record_id']) && $params['record_id'] != '')
		{
			$record_id = $params['record_id'];
			$delete_cotis = $cotis_ops->delete_cotis($record_id);
			if(true === $delete_cotis)
			{
				$this->SetMessage('Cotisation supprimée.');
				
			}
			else
			{
				$this->SetMessage('La suppression a échouée !');
			}
		}
		else
		{
			$this->SetMessage('Une erreur est survenue !');
		}
		$this->Redirect($id, 'defaultadmin', $returnid);
	break;
	//supprime une appartenance à un groupe et le paiement associé
	 case "delete_belongs" :
		$error = 0;
		if(isset($params['ref_action']) && $params['ref_action'] != '')
		{
			$ref_action = $params['ref_action'];
		}
		else
		{
			$error++;
		}
	
		if($error<=0)
		{
			
			$suppression = $cotis_ops->delete_user_paiement($ref_action);
			if(true === $suppression)
			{
				$this->SetMessage('Cotisation supprimée. Paiement aussi');
			}
			else
			{
				$this->SetMessage('Une erreur s\'est produite.');
			}
			
			
		}
		$this->Redirect($id, 'defaultadmin', $returnid);
	break;
	
	case "raz" :
		$remise = $cotis_ops->raz();
		if(true === $remise)
		{
			$this->SetMessage('Remise à zéro effectuée');
		}
		else
		{
			$this->SetMessage('Echec !');
		}
		$this->Redirect($id, 'defaultadmin', $returnid);
	break;
	
	case "payment" :
	 	if(isset($params['ref_action']) && $params['ref_action'] !='')
		{
			$ref_action = $params['ref_action'];
		
			if($use_paiements == '1')
			{
				$details = $paiements_ops->details_paiement($ref_action);
				//var_dump($details);
				if(!false == $details)
				{
					$add_regl = $paiements_ops->add_paiement($details['licence'], $ref_action, 'R','Cotisations',$details['nom'], $details['tarif'], '1', '1','1','2020-2021');
				}
				else
				{
					$tab = explode('_', $ref_action);
					//on va chercher qqs infos sur le type de cotiz
					$details = $cotis_ops->details_type_cotiz($tab[2]);
					$categorie = 'R';
					$module = 'Cotisations';
					$statut = 1;
					$actif = 1;
					$regle = 1;
					$add_regl = $paiements_ops->add_paiement($tab[1], $ref_action, $categorie, $module, $details['nom'], $details['tarif'], $statut, $actif, $regle, $exercice);
				}
				var_dump($add_regl);
				
				if(false === $add_regl)
				{
					$this->SetMessage('Paiement échoué');
				}
				else
				{
					$this->SetMessage('Paiement Ok');
				}
			}
			$add_reglement = $cotis_ops->add_reglement($ref_action);
		}
		if(isset($params['record_id']) && $params['record_id'] !='')
		{
			$record_id = $params['record_id'];
		}
		$this->RedirectToAdminTab('adherents');//($id, 'view_group_users', $returnid, array("id_cotisation"=>$record_id));//ToAdminTab('adherents');
	break;
	
	case "payment2" :
	 	if(isset($params['ref_action']) && $params['ref_action'] !='')
		{
			$ref_action = $params['ref_action'];
			$montant = $paiements_ops->montant_tarif($ref_action);
			$add_regl = $paiements_ops->add_reglement_total($ref_action, $montant);
			if(false === $add_regl)
			{
				$this->SetMessage('Paiement échoué');
			}
			else
			{
				$this->SetMessage('Paiement Ok');
			}
		}
		
		$this->RedirectToAdminTab('adherents');
	break;
	//ajoute un utilisateur à un groupe
	case "add_member_to_group" :
	{
		$gp = new groups;
		if(isset($params['genid']) && isset($params['id_group']))
		{
			$add_user_to_group = $gp->assign_user_to_group($params['id_group'], $params['genid']);
			$this->Redirect($id, 'defaultadmin', $returnid);
		}
	}
}

//$this->RedirectToAdminTab('adherents');

?>