<?php
if (!isset($gCms)) exit;
debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Cotisations use'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('types_cotis');
	}

//on récupère les valeurs
//pour l'instant pas d'erreur
$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
	
		
		
		if (isset($params['cotis_id']) && $params['cotis_id'] !='')
		{
			$cotis_id = $params['cotis_id'];
			$cotis_ops = new cotisationsbis;
			
			$delete = $cotis_ops->delete_categ_to_cotis($cotis_id);
			//On supprime tt d'abord
			
			if(true === $delete)
			{
				$VM = 0;
				if (isset($params['VM']) && $params['VM'] !='')
				{
					$VM = 'VM';//$params['VM'];
					$cotis_ops->add_categ_to_cotis($cotis_id,$VM);
				}
				$VF = 0;
				if (isset($params['VF']) && $params['VF'] !='')
				{
					$VF = 'VF';
					$cotis_ops->add_categ_to_cotis($cotis_id,$VF);
				}
				$SM = 0;
				if (isset($params['SM']) && $params['SM'] !='')
				{
					$SM = 'SM';
					$cotis_ops->add_categ_to_cotis($cotis_id,$SM);
				}
				$SF = 0;
				if (isset($params['SF']) && $params['SF'] !='')
				{
					$SF = 'SF';
					$cotis_ops->add_categ_to_cotis($cotis_id,$SF);
				}
				$JM = 0;
				if (isset($params['JM']) && $params['JM'] !='')
				{
					$JM = 'JM';
					$cotis_ops->add_categ_to_cotis($cotis_id,$JM);
				}
				$JF = 0;
				if (isset($params['JF']) && $params['JF'] !='')
				{
					$JF = 'JF';
					$cotis_ops->add_categ_to_cotis($cotis_id,$JF);
				}
				$CM = 0;
				if (isset($params['CM']) && $params['CM'] !='')
				{
					$CM = 'CM';
					$cotis_ops->add_categ_to_cotis($cotis_id,$CM);
				}
				$CF = 0;
				if (isset($params['CF']) && $params['CF'] !='')
				{
					$CF = 'CF';
					$cotis_ops->add_categ_to_cotis($cotis_id,$CF);
				}
				$MM = 0;
				if (isset($params['MM']) && $params['MM'] !='')
				{
					$MM = 'MM';
					$cotis_ops->add_categ_to_cotis($cotis_id,$MM);
				}
				$MF = 0;
				if (isset($params['MF']) && $params['MF'] !='')
				{
					$MF = 'MF';
					$cotis_ops->add_categ_to_cotis($cotis_id,$MF);
				}
				$BM = 0;
				if (isset($params['BM']) && $params['BM'] !='')
				{
					$BM = 'BM';
					$cotis_ops->add_categ_to_cotis($cotis_id,$BM);
				}
				$BF = 0;
				if (isset($params['BF']) && $params['BF'] !='')
				{
					$BF = 'BF';
					$cotis_ops->add_categ_to_cotis($cotis_id,$BF);
				}
				$PM = 0;
				if (isset($params['PM']) && $params['PM'] !='')
				{
					$PM = 'PM';
					$cotis_ops->add_categ_to_cotis($cotis_id,$PM);
				}
				$PF = 0;
				if (isset($params['PF']) && $params['PF'] !='')
				{
					$PF = 'PF';
					$cotis_ops->add_categ_to_cotis($cotis_id,$PF);
				}
			}
			else
			{
				$this->SetMessage('Suppression impossible des catégories de cette cotisation');
			}
			
						
		}
		else
		{
			$this->SetMessage('Paramètres manquants');
		}		
		
		
		
	/*
		
		
		
		
				
		//on calcule le nb d'erreur
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('types_cotis');
		}
		else // pas d'erreurs on continue
		{
			
			
			
			
			if($edit == 0)
			{
				$query = "INSERT INTO ".cms_db_prefix()."module_cotisations_types_cotisations (nom, description, tarif,actif) VALUES ( ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($nom, $description,$tarif, $actif));

			}
			else
			{
				$query = "UPDATE ".cms_db_prefix()."module_cotisations_types_cotisations SET nom = ?, description = ?, tarif = ?, actif = ? WHERE id = ?";
				$dbresult = $db->Execute($query, array($nom, $description, $tarif,$actif,$record_id));
				
				
			}
			
			
			
		}		
	//	echo "la valeur de edit est :".$edit;
		
		
	
*/			
		


$this->RedirectToAdminTab('cotis');//($id,'add_types_cotis_categ',$returnid, array('record_id'=>$record_id));

?>