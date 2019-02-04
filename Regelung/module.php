<?

$mod = 0;

class ALGModus extends IPSModule
	{
		
		public function Create()
		{
			//Never delete this line!
			parent::Create();
						
			if (!IPS_VariableProfileExists("ALG-Modus")) {
			
				IPS_CreateVariableProfile("ALG-Modus", 1); // 0 boolean, 1 int, 2 float, 3 string,
				IPS_SetVariableProfileValues("ALG-Modus", 1, 3, 0);
				IPS_SetVariableProfileDigits("ALG-Modus", 0);
				IPS_SetVariableProfileAssociation("ALG-Modus", 1, "Anwesend", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG-Modus", 2, "Party", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG-Modus", 3, "Abwesend", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG-Modus", 4, "Auto", "", 0xFFFFFF);
			}
		
			
			//___In_IPS_zurverfügungstehende_Variabeln_______________________________________________
			$this->RegisterVariableInteger("Mod", "Modus", "ALG-Modus", 1);
			$this->RegisterVariableBoolean("HZ", "Heizung", "~Switch", 5);
			$this->RegisterVariableBoolean("MD", "Meldung", "~Switch", 10);
			//$this->RegisterVariableFloat("SW", "Sollwert", "~Temperature.Room", 3);
			//$this->RegisterVariableBoolean("ZP_Conf", "ZP_Confort", "~Switch", 11);
			
			//___Modulvariabeln______________________________________________________________________
			$this->RegisterPropertyInteger("SWS", 1);			
			//$this->RegisterPropertyBoolean("Abw", true);
			
			
			//$this->RegisterPropertyInteger("UpdateWeatherInterval", 30);
			//$this->RegisterPropertyString("APIkey", 0);

			
		}
	
	        public function ApplyChanges() {
            		//Never delete this line!
            		parent::ApplyChanges();
			
				
            		//$triggerIDProg = $this->ReadPropertyInteger("TrigProgramm");
            		//$this->RegisterMessage($triggerIDProg, 10603 /* VM_UPDATE */);
			
			//$triggerIDConf = $this->ReadPropertyInteger("TrigConfort");
			//$this->RegisterMessage($triggerIDConf, 10603 /* VM_UPDATE */);
			
			//$triggerIDAbw = $this->ReadPropertyInteger("TrigAbwesend");
			//$this->RegisterMessage($triggerIDAbw, 10603 /* VM_UPDATE */);
			

			
			//Standartaktion Aktivieren
			$this->VariabelStandartaktion();
			
        	}
	
	        public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) {
		//global $sws, $zp_conf, $sws_abw, $abw, $prog, $sw, $sw_abs;
            		//$triggerIDProg = $this->ReadPropertyInteger("TrigProgramm");
			//$triggerIDConf = $this->ReadPropertyInteger("TrigConfort");
			//$triggerIDAbw = $this->ReadPropertyInteger("TrigAbwesend");
	
			//if (($SenderID == $triggerIDProg) && ($Message == 10603)){// && (boolval($Data[0]))){
				//$prog = getValue($this->GetIDForIdent("prog"));
				//$sw = getValue($this->GetIDForIdent("SW"));
				//$sw_abs = getValue($this->GetIDForIdent("SW_Abs"));
				//$this->SWRegler();
           		//}

        }
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * ABC_Calculate($id);
        *
        */
	
	public function RequestAction($key, $value){
		global $mod;
        	switch ($key) {
        		case 'Mod':
				$mod = $value;
				//$zp_conf = getValue($this->GetIDForIdent("ZP_Conf"));
				//$sws_abw = getValue($this->GetIDForIdent("SWS_Abw"));
				//$abw = getValue($this->GetIDForIdent("Abw"));
				$this->ALGAuswahl();
            		break;
				
        	}
		
        $this->SetValue($key, $value);	
		
   	}
	
	
	
	public function VariabelStandartaktion(){
		
		$this->EnableAction("Mod");
		$this->EnableAction("MD");
		$this->EnableAction("HZ");
		
	}
		
	
		public function ZeitPro(){
		
	
		$KategorieID_Settings = IPS_GetCategoryIDByName("Settings", 0);
		//$KategorieID_Settings = IPS_GetCategoryIDByName("Einstellungen", $KategorieID_Heizung);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Settings);
				
		$EreignisID_HZ_01 =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_HZ_01, "Von");
		IPS_SetParent($EreignisID_HZ_01, $InstanzID);
		IPS_SetPosition($EreignisID_HZ_01, 6);
		IPS_SetEventCyclic($EreignisID_HZ_01, 1 /* Täglich */ ,5,0,0,0,0);
		
		$EreignisID_HZ_02 =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_HZ_02, "Bis");
		IPS_SetParent($EreignisID_HZ_02, $InstanzID);
		IPS_SetPosition($EreignisID_HZ_02, 7);
		IPS_SetEventCyclic($EreignisID_HZ_02, 1 /* Täglich */ ,5,0,0,0,0);
			
		$EreignisID_MD_01 =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_MD_01, "Von ");
		IPS_SetParent($EreignisID_MD_01, $InstanzID);
		IPS_SetPosition($EreignisID_MD_01, 11);
		IPS_SetEventCyclic($EreignisID_MD_01, 1 /* Täglich */ ,5,0,0,0,0);
		
		$EreignisID_MD_02 =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_MD_02, "Bis ");
		IPS_SetParent($EreignisID_MD_02, $InstanzID);
		IPS_SetPosition($EreignisID_MD_02, 12);
		IPS_SetEventCyclic($EreignisID_MD_02, 1 /* Täglich */ ,5,0,0,0,0);
	
		//IPS_SetHidden($this->GetIDForIdent("ZP_Conf"), true);
		//IPS_SetHidden($this->GetIDForIdent("Abw"), true);
		
	}
	
	public function ALGAuswahl(){
		
	global $mod;
		//$test = getValue($this->GetIDForIdent("SWS_Abw"));
		
		$KategorieID_Settings = IPS_GetCategoryIDByName("Settings", 0);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Settings);
		$VariabelID_HZ_Ab = IPS_GetEventIDByName("Von", $InstanzID);
		$VariabelID_HZ_An = IPS_GetEventIDByName("Bis", $InstanzID);
		$VariabelID_MD_Ab = IPS_GetEventIDByName("Von ", $InstanzID);
		$VariabelID_MD_An = IPS_GetEventIDByName("Bis ", $InstanzID);
		
		if($mod == 2){
			//SetValue($this->GetIDForIdent("prog"), 0);
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($VariabelID_HZ_Ab, true);
			IPS_SetHidden($VariabelID_HZ_An, true);
			IPS_SetHidden($VariabelID_MD_Ab, true);
			IPS_SetHidden($VariabelID_MD_An, true);
			//echo "Aus";
		}
		else if($mod == 3){
			IPS_SetHidden($this->GetIDForIdent("MD"), false);
			IPS_SetHidden($this->GetIDForIdent("HZ"), false);
			IPS_SetHidden($VariabelID_HZ_Ab, true);
			IPS_SetHidden($VariabelID_HZ_An, true);
			IPS_SetHidden($VariabelID_MD_Ab, true);
			IPS_SetHidden($VariabelID_MD_An, true);
			//echo "Hand";
		}
		else if($mod == 4){
			IPS_SetDisabled($this->GetIDForIdent("MD"), true);
			IPS_SetDisabled($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($this->GetIDForIdent("MD"), false);
			IPS_SetHidden($this->GetIDForIdent("HZ"), false);
			IPS_SetHidden($VariabelID_HZ_Ab, false);
			IPS_SetHidden($VariabelID_HZ_An, false);
			IPS_SetHidden($VariabelID_MD_Ab, false);
			IPS_SetHidden($VariabelID_MD_An, false);
			//echo "Hand";
		}
		else{
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
		}
		
	}
		
	
	
	public function Test(){
		
		$this->EnableAction("SW_Abs");
		
	}
	
    
		   
    }
?>
