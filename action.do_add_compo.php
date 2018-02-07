<?php

if( !isset($gCms) ) exit;

	if (!$this->CheckPermission('Compositions use'))
  	{
    		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
   
  	}

	if( isset($params['cancel']) )
  	{
    		$this->RedirectToAdminTab('compos');
    		return;
  	}

debug_display($params, 'Parameters');
$db =& $this->GetDb();
$now = date('Y-m-d');
$ping = cms_utils::get_module('Ping');
$saison = $ping->GetPreference('saison_en_cours');//new Ping();
$designation = '';//le message final
$error = 0;//on initie un compteur d'erreur, 0 par défaut

if(isset($params['edition']) && $params['edition'] != '')
{
	$edit = $params['edition'];
}
else
{
	$edit = 0;//il s'agit d'un ajout de commande
}



if(isset($params['record_id']) && $params['record_id'] != '')
{
	$ref_action = $params['record_id'];
}
if(isset($params['idepreuve']) && $params['idepreuve'] != '')
{
	$idepreuve = $params['idepreuve'];
}
if(isset($params['journee']) && $params['journee'] != '')
{
	$journee = $params['journee'];
}
if(isset($params['phase']) && $params['phase'] != '')
{
	$phase = $params['phase'];
}
$actif = '';
if(isset($params['actif']) && $params['actif'] != '')
{
	$actif = $params['actif'];
}

$statut = 0;
if(isset($params['statut']) && $params['statut'] != '')
{
	$statut = $params['statut'];
}


if($edit == 0)
{
	//on fait d'abord l'insertion 
	$query1 = "INSERT INTO ".cms_db_prefix()."module_compositions_journees (id, ref_action, idepreuve, journee, actif, statut, phase,saison) VALUES ('', ?, ?, ?, ?, ?, ?, ?)";
	$dbresult1 = $db->Execute($query1, array($ref_action, $idepreuve, $journee, $actif, $statut, $phase, $saison));
	if($dbresult1)
	{
		//on vérifie qu'il existe une liste de joueurs pour cette épreuve
		$comp_ops = new compositionsbis;
		$liste = $comp_ops->liste_exists($idepreuve);
		if(FALSE === $liste)
		{
			//la liste n'existe pas, il faut la créér
			$this->Redirect($id, 'creer_liste', $returnid, array("idepreuve"=>$idepreuve));
		}
		else
		{
			$this->RedirectToAdminTab('compos');
		}
	}
}
/*
else
{
	//il s'agit d'une mise à jour !
	//on récupère les éléments d'origine des articles de cette commande
	$service = new commandes_ops();
	$query4 = "SELECT id, fk_id, libelle_commande, categorie_produit, fournisseur, quantite, ep_manche_taille, couleur, prix_total,commande,commande_number  FROM ".cms_db_prefix()."module_commandes_cc_items WHERE commande_number = ?";
	$dbresult4 = $db->Execute($query4, array($commande_number));

	if($dbresult4 && $dbresult4->RecordCount()>0)
	{
		while($row4 = $dbresult4->FetchRow())
		{
			
		}

	}
		//les articles sont en stock
			
	$query2 = "UPDATE ".cms_db_prefix()."module_commandes_cc SET date_modified = ?, libelle_commande = ?, statut_commande = ?, remarques = ? WHERE commande_number = ?";
	$dbresult2 = $db->Execute($query2, array($now, $libelle_commande, $statut_commande, $remarques, $commande_number));
	
	
	
		
		
		
*/	
	
	$this->SetMessage($designation);
	$this->RedirectToAdminTab('compos');



#
# EOF
#
?>