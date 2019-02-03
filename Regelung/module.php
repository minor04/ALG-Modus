<?

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
			$this->RegisterVariableBoolean("MD", "Meldung", "~Switch", 5);
			$this->RegisterVariableBoolean("HZ", "Heizung", "~Switch", 6);
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
		global $mod ;
        	switch ($key) {
        		case 'Mod':
				$mod = $value;
				//$zp_conf = getValue($this->GetIDForIdent("ZP_Conf"));
				//$sws_abw = getValue($this->GetIDForIdent("SWS_Abw"));
				//$abw = getValue($this->GetIDForIdent("Abw"));
				//$this->ProgrammAuswahl();
            		break;
				
        	}
		
        $this->SetValue($key, $value);	
		
   	}
	
	
	
	public function VariabelStandartaktion(){
		
		$this->EnableAction("Mod");
		
	}
		
	
		public function ZeitPro(){
		
	
		$KategorieID_Settings = IPS_GetCategoryIDByName("Settings", 0);
		//$KategorieID_Settings = IPS_GetCategoryIDByName("Einstellungen", $KategorieID_Heizung);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Settings);
			
		$EreignisID =IPS_CreateEvent(2);
		IPS_SetName($EreignisID, "Zeitschaltprogramm");
		IPS_SetParent($EreignisID, $InstanzID);
		IPS_SetPosition($EreignisID, 10);
			
		IPS_SetEventScheduleGroup($EreignisID, 0, 31); //Mo - Fr (1 + 2 + 4 + 8 + 16)
		IPS_SetEventScheduleGroup($EreignisID, 1, 96); //Sa + So (32 + 64)
			
		IPS_SetEventScheduleAction($EreignisID, 0, "Eco", 0xFF8080, "SetValue(36402, false);");
		IPS_SetEventScheduleAction($EreignisID, 1, "Confort", 0xFF0000, "SetValue(36402, true);");
		
		IPS_SetEventScheduleGroupPoint($EreignisID, 0, 0, 0, 0, 0, 0); //Um 0:00 Aktion mit ID 0 "Eco" aufrufen
		IPS_SetEventScheduleGroupPoint($EreignisID, 0, 1, 6, 0, 0, 1); //Um 6:00 Aktion mit ID 1 "Comfort" aufrufen
		IPS_SetEventScheduleGroupPoint($EreignisID, 0, 2, 8, 0, 0, 0); //Um 8:00 Aktion mit ID 0 "Eco" aufrufen
		IPS_SetEventScheduleGroupPoint($EreignisID, 0, 3, 16, 0, 0, 1); //Um 16:00 Aktion mit ID 1 "Comfort" aufrufen
		IPS_SetEventScheduleGroupPoint($EreignisID, 0, 4, 22, 0, 0, 0); //Um 22:00 Aktion mit ID 0 "Eco" aufrufen
		
		IPS_SetEventScheduleGroupPoint($EreignisID, 1, 10, 0, 0, 0, 0); //Um 0:00 Aktion mit ID 0 "Eco" aufrufen
		IPS_SetEventScheduleGroupPoint($EreignisID, 1, 11, 7, 0, 0, 1); //Um 7:00 Aktion mit ID 1 "Comfort" aufrufen
		IPS_SetEventScheduleGroupPoint($EreignisID, 1, 12, 22, 0, 0, 0); //Um 22:00 Aktion mit ID 0 "Eco" aufrufen
		
		
		$EreignisID =IPS_CreateEvent(1);
		IPS_SetName($EreignisID, "Von");
		IPS_SetParent($EreignisID, $InstanzID);
		IPS_SetPosition($EreignisID, 13);
		IPS_SetEventCyclic($EreignisID, 1 /* Täglich */ ,5,0,0,0,0);
		
		$EreignisID_02 =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_02, "Bis");
		IPS_SetParent($EreignisID_02, $InstanzID);
		IPS_SetPosition($EreignisID_02, 14);
		IPS_SetEventCyclic($EreignisID_02, 1 /* Täglich */ ,5,0,0,0,0);
	
		//IPS_SetHidden($this->GetIDForIdent("ZP_Conf"), true);
		//IPS_SetHidden($this->GetIDForIdent("Abw"), true);
		
	}
		
	
	
	public function Test(){
		
		$this->EnableAction("SW_Abs");
		
		
	}
	
    
		   
    }
?>
