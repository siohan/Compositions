<?php
if (!isset($gCms)) exit;

//debug_display($_POST, 'Parameters');

$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
$cotis_ops = new cotisationsbis;	
		
		
	
				
		if (isset($_POST['nom']) && $_POST['nom'] !='')
		{
			$id_option = $_POST['nom'];		
		}
		else
		{
			$error++;
		}
		
		if (isset($_POST['genid']) && $_POST['genid'] !='')
		{
			$genid = $_POST['genid'];
		}
		else
		{
			$error++;
		}
	
			
		if($error < 1)
		{
				$del_rep = $cotis_ops->delete_user_cotis($genid);
				
					$ref_action = $this->random_string(15);
					if(true === is_array($id_option) && count($id_option)>0)
					{
						foreach($id_option as $key)
						{
							$add = $cotis_ops->add_user_cotis($ref_action, $key, $genid);
						}
					}
					else
					{
						$add = $cotis_ops->add_reponse($ref_action, $id_option, $genid);
					}
						
					if(true === $add )
					{
					//$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_feu_inscription.tpl'), null, null, $smarty);
					//$tpl->assign('final_msg', $final_msg);	
					echo '<h2>Réponse(s) ajoutée(s)</h2><p>Merci d\'avoir répondu !</p>';
						
					}
					else
					{
						echo 'Inscription non ajoutée !!';
					}
				
		
		}
		else
		{
			$this->SetMessage('Parametre(s) manquant(s)');
		}
			


?>