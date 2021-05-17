<?php
if (!isset($gCms)) exit;
//require_once(dirname(__FILE__).'/include/prefs.php');
debug_display($params, 'Parameters');
if (!$this->CheckPermission('Cotisations use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//if()
$annee = date('Y');
//on récupère les valeurs
//pour l'instant pas d'erreur
$error = 0;
		
		$licence1 = '';
		if (isset($params['licence1']) && $params['licence1'] != '')
		{
			$licence1 = $params['licence1'];
		}
		else
		{
			$error++;
		}
	
		if($error ==0)
		{
			//on vire toutes les données de cette compet avant 
		
				$genid = array();
				if (isset($params['genid']) && $params['genid'] != '')
				{
					$genid = $params['genid'];
				}
			
			//	$cotisation_ops = new cotisationsbis();
			//	$paiements_ops = cms_utils::get_module('Paiements');
				//$paiements_ops = new paiements();
				
				foreach($genid as $key=>$value)
				{
					$ref_action = $this->random_string(15);
					$query2 = "INSERT IGNORE INTO ".cms_db_prefix()."module_cotisations_belongs (ref_action,id_cotisation,genid) VALUES ( ?, ?, ?)";
					$dbresultat = $db->Execute($query2, array($ref_action,$value,$licence1));
					//la requete a fonctionné ? On ajoute à la table Paiements
					if($dbresultat)
					{
						//on ajoute 
						$tableau = $this->types_cotisations($value);
						if(is_array($tableau))
						{
							$nom = $tableau['nom'];
							//echo $nom;
							$tarif = $tableau['tarif'];

						
							$query = "INSERT INTO ".cms_db_prefix()."module_paiements_produits (licence,date_created,ref_action, module,nom, tarif) VALUES (?, ?, ?, ?, ?, ?)";
							$dbresult = $db->Execute($query, array($licence1,time(),$ref_action,'Cotisations',$nom, $tarif));
							if(!$dbresult)
							{

								$error = $db->ErrorMsg();
								echo $error;
							}
							
							$add = $this->add_paiement($licence1,$ref_action,$nom,$tarif);
						//	var_dump($add);
						}//var_dump($tableau);
						//
						
						
					}
				}
			$this->SetMessage('Cotisation(s) ajouté(s) à ce membre !');
			
				
				
		}
		else
		{
			echo "Il y a des erreurs !";
		}
		


$this->Redirect($id, 'view_adherent_cotis',$returnid, array("genid"=>$licence1));

?>