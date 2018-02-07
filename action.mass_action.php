<?php
//ce fichier fait des actions de masse, il est appelé depuis l'onglet de récupération des infos sur les joueurs
if( !isset($gCms) ) exit;
if (!$this->CheckPermission('Compositions use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
$comp_ops = new compositions();
debug_display($params, 'Parameters');
var_dump($params['sel']);
$db =& $this->GetDb();
if (isset($params['submit_massaction']) && isset($params['actiondemasse']) )
  {
     if( isset($params['sel']) && is_array($params['sel']) &&
	count($params['sel']) > 0 )
      	{
        	
		switch($params['actiondemasse'])
		{
			case "lock" :
			
	  		{
	    			$act = $comp_ops->lock($sel);
	  		}
			if(TRUE === $act)
			{
				$this->SetMessage('Verrous activés');
			}
			else
			{
				$this->SetMessage($act);
			}
			$this->RedirectToAdminTab('compos');
			break;
	
						
			case "unlock" :
				
			
			break;
			/*
			case "sexe" :
				$id_sel = implode("-",$params['sel']);
				$this->Redirect($id,'change_paiement',$returnid, array("sel"=>$id_sel));
			
			break;
			
			case "item_categorie" :
				$id_sel = implode("-",$params['sel']);
				$this->Redirect($id,'change_item_categorie',$returnid, array("sel"=>$id_sel));
			
			break;
			
			case "item_fournisseur" :
				$id_sel = implode("-",$params['sel']);
				$this->Redirect($id,'change_item_fournisseur',$returnid, array("sel"=>$id_sel));
			
			break;
			
			case "item_marque" :
				$id_sel = implode("-",$params['sel']);
				$this->Redirect($id,'change_item_marque',$returnid, array("sel"=>$id_sel));
			
			break;
			
			case "item_prix" :
				$id_sel = implode("-",$params['sel']);
				$this->Redirect($id,'change_item_prix',$returnid, array("sel"=>$id_sel));
			
			break;
			
			case "item_reduction" :
				$id_sel = implode("-",$params['sel']);
				$this->Redirect($id,'change_item_reduction',$returnid, array("sel"=>$id_sel));
			
			break;
			
			case "item_statut" :
				$id_sel = implode("-",$params['sel']);
				$this->Redirect($id,'change_item_statut',$returnid, array("sel"=>$id_sel));
			
			break;
			*/
			
			
	
      		}//fin du switch
  	}
	else
	{
		$this->SetMessage('PB de sélection de masse !!');
		$this->RedirectToAdminTab('recuperation');
	}
}
/**/
#
# EOF
#
?>