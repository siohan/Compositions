<?php
if (!isset($gCms)) exit;
//require_once(dirname(__FILE__).'/include/prefs.php');
debug_display($_POST, 'Parameters');
if ( !$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
if(isset($_POST['cancel']))
{
	$this->Redirect();
}
$use_paiements = $this->GetPreference('use_paiements');

$exercice = $this->GetPreference('exercice');
$cotis_ops = new cotisationsbis;
$message = '';
$annee = date('Y');
//on récupère les valeurs
//pour l'instant pas d'erreur
$error = 0;
		
		$genid = '';
		if (isset($_POST['genid']) && $_POST['genid'] != '')
		{
			$genid = $_POST['genid'];
		}
		else
		{
			$error++;
		}
		
	
		if($error ==0)
		{
			
			$group = array();
			if (isset($_POST['group']) && $_POST['group'] != '')
			{
				$group = $_POST['group'];
			}
			
			
			if(true == $use_paiements)
			{
				$paiements_ops = new paiementsbis();
			}
			
			//on supprime toutes les appartenances de cet utilisateur
			
			//on supprime aussi ds Paiements ? Il faudrait bloquer les cotiz déjà payées....
			
			$del = $cotis_ops->delete_user_cotis($genid);
			
			foreach($group as $key=>$value)
			{
				$ref_action = 'Cotiz_'.$genid.'_'.$value;//$this->random_string(15);
				$add_cotis_to_user = $cotis_ops->add_user_cotis($ref_action,$value, $genid);
				
				//la requete a fonctionné ? On ajoute à la table Paiements
				if(true == $add_cotis_to_user)
				{
					//on ajoute 
					$message.="Adhérent(s) ajouté(s).";
					if(true == $use_paiements)
					{
						$tableau = $cotis_ops->types_cotisations($value);
						//var_dump($nom);
						if(is_array($tableau))
						{
							$nom = $tableau['nom'];
							$tarif = $tableau['tarif'];
							$module = 'Cotisations';
							$categorie = 'R';
							$actif = 1;
							$statut = 1;
							$regle = 1;
							$add = $paiements_ops->add_paiement($genid,$ref_action,$categorie,$module,$nom,$tableau['tarif'], $actif,$statut,$regle, $exercice);
							//$add = $paiements_ops->add_paiement($genid,$ref_action,$module,$nom,$tarif);
							if(true === $add)
							{
								$message.=" Envoyé au module Paiements.";
							}
							//var_dump($add);

						}
					}
				
				}
			}
			
				
				
		}
		else
		{
			echo "Il y a des erreurs !";
		}
		

$this->SetMessage($message);
$this->Redirect($id, 'admin_joueurs_tab', $returnid);

?>