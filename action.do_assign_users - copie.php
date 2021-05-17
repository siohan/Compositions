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
		
				$genid = '';
				if (isset($_POST['genid']) && $_POST['genid'] != '')
				{
					$genid = $_POST['genid'];
				}
				$cotisation_ops = new cotisationsbis();
				$paiements_ops = new paiementsbis();
				foreach($genid as $key=>$value)
				{
					$ref_action = $this->random_string(15);
					$query2 = "INSERT IGNORE INTO ".cms_db_prefix()."module_cotisations_belongs (ref_action,id_cotisation,genid) VALUES ( ?, ?, ?)";
					//echo $query2;
					$dbresultat = $db->Execute($query2, array($ref_action,$record_id,$value));
					//la requete a fonctionné ? On ajoute à la table Paiements
					if($dbresultat)
					{
						//on ajoute 
						$message.="Adhérent(s) ajouté(s) au groupe.";
						$tableau = $cotisation_ops->types_cotisations($record_id);
						//var_dump($nom);
						if(is_array($tableau))
						{
							$nom = $tableau['nom'];
							$tarif = $tableau['tarif'];
							$module = 'Cotisations';
							$add = $paiements_ops->add_paiement($value,$ref_action,$module,$nom,$tarif);
							if(true === $add)
							{
								$message.=" Paiement en attente de règlement.";
							}
							//var_dump($add);
							
						}
						
					}
				}
			$this->SetMessage($message);
			
				
				
		}
		else
		{
			echo "Il y a des erreurs !";
		}
		


$this->RedirectToAdminTab('types_cotis');

?>