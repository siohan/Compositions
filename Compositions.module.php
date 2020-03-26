<?php

#-------------------------------------------------------------------------
# Module : Compositions - 
# Version : 0.5, Sc
# Auteur : AssoSimple
#-------------------------------------------------------------------------
/**
 *
 * @author AssoSimple 
 * @since 0.1
 * @version $Revision: 1 $
 * @modifiedby $LastChangedBy: Claude
 * @license GPL
 **/

class Compositions extends CMSModule
{
  
  function GetName() { return 'Compositions'; }   
  function GetFriendlyName() { return $this->Lang('friendlyname'); }   
  function GetVersion() { return '0.5'; }  
  function GetHelp() { return $this->Lang('help'); }   
  function GetAuthor() { return 'AssoSimple'; } 
  function GetAuthorEmail() { return 'contact@asso-simple.fr'; }
  function GetChangeLog() { return $this->Lang('changelog'); }
    
  function IsPluginModule() { return true; }
  function HasAdmin() { return true; }   
  function GetAdminSection() { return 'content'; }
  function GetAdminDescription() { return $this->Lang('moddescription'); }
 
  function VisibleToAdminUser()
  {
    	return 
		$this->CheckPermission('Compositions use');
	
  }
  
  
  function GetDependencies()
  {
	return array('Adherents'=>'0.3.5');
  }

  

  function MinimumCMSVersion()
  {
    return "2.0";
  }

  
  function SetParameters()
  { 
  	$this->RegisterModulePlugin();
	$this->RestrictUnknownParams();
	$this->SetParameterType('genid', CLEAN_INT);
	$this->SetParameterType('ref_action', CLEAN_STRING);
	$this->SetParameterType('ref_equipe', CLEAN_INT);
	$this->SetParameterType('idepreuve', CLEAN_INT);
	$this->SetParameterType('record_id', CLEAN_STRING);

}

function InitializeAdmin()
{
  	return parent::InitializeAdmin();
	$this->SetParameters();
	//$this->CreateParameter('pagelimit', 100000, $this->Lang('help_pagelimit'));
}

public function HasCapability($capability, $params = array())
{
   if( $capability == 'tasks' ) return TRUE;
   return FALSE;
}

public function get_tasks()
{
   $obj = array();
	//$obj[0] = new PingRecupFfttTask();
   	//$obj[1] = new PingRecupSpidTask();  
	//$obj[2] = new PingRecupRencontresTask();
return $obj; 
}

  function GetEventDescription ( $eventname )
  {
    return $this->Lang('event_info_'.$eventname );
  }
     
  function GetEventHelp ( $eventname )
  {
    return $this->Lang('event_help_'.$eventname );
  }

  function InstallPostMessage() { return $this->Lang('postinstall'); }
  function UninstallPostMessage() { return $this->Lang('postuninstall'); }
  function UninstallPreMessage() { return $this->Lang('really_uninstall'); }
  function random_string($car) {
	$string = "";
	$chaine = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
		$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
  }

  
  function _SetStatus($oid, $status) {
    //...
  }




} //end class
?>
