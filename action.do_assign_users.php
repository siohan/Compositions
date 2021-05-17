<?php
if (!isset($gCms)) exit;
//require_once(dirname(__FILE__).'/include/prefs.php');
debug_display($_POST, 'Parameters');
if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
if(isset($_POST['cancel']))
{
	$this->RedirectToAdminTab('types_cotis');
}
$message = '';
$annee = date('Y');
$use_paiements = $this->GetPreference('use_paiements');
//on récupère les valeurs
//pour l'instant pas d'erreur
$error = 0;
		
		$record_id = '';
		if (isset($_POST['record_id']) && $_POST['record_id'] != '')
		{
			$record_id = $_POST['record_id'];
		}
		
	
		if($error ==0)
		{
			//on vire toutes les données de cette compet avant 
		
				$genid = array();
				if (isset($_POST['nom']) && $_POST['nom'] != '')
				{
					$genid = $_POST['nom'];
				}
				$cotisation_ops = new cotisationsbis();
				if(true == $this->GetPreference('use_paiements'))
				{
					$paiements_ops = new paiementsbis;
				}
				$i = 0;
				foreach($genid as $key=>$value)
				{
					$ref_action = 'Cotiz_'.$value.'_'.$record_id;//$this->random_string(15);
					$query2 = "INSERT IGNORE INTO ".cms_db_prefix()."module_cotisations_belongs (ref_action,id_cotisation,genid) VALUES ( ?, ?, ?)";
					//echo $query2;
					$dbresultat = $db->Execute($query2, array($ref_action,$record_id,$value));
					//la requete a fonctionné ? On ajoute à la table Paiements
					if($dbresultat)
					{
						$i++;
						//on ajoute 
						if(true == $this->GetPreference('use_paiements'))
						{
							$tableau = $cotisation_ops->types_cotisations($record_id);
							//var_dump($nom);
							if(is_array($tableau))
							{
								$nom = $tableau['nom'];
								$tarif = $tableau['tarif'];
								$module = 'Cotisations';
								$add = $paiements_ops->add_paiement($value,$ref_action,$module,$nom,$tarif);
							}
						}
						
						
					}
					$message.=$i." adhérent(s) ajouté(s) au groupe.";
				}
			$this->SetMessage($message);
			
				
				
		}
		else
		{
			echo "Il y a des erreurs !";
		}
		


$this->RedirectToAdminTab('types_cotis');

?>