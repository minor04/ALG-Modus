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
			}
		
			
			//___In_IPS_zurverfügungstehende_Variabeln_______________________________________________
			$this->RegisterVariableInteger("Mod", "Modus", "ALG-Modus", 1);
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
		//global $sws, $zp_conf, $sws_abw, $abw, $prog, $sw, $sw_abs, $sws_abw;
        	//switch ($key) {
        		//case 'SWS':
				//$sws = $value;
				//$zp_conf = getValue($this->GetIDForIdent("ZP_Conf"));
				//$sws_abw = getValue($this->GetIDForIdent("SWS_Abw"));
				//$abw = getValue($this->GetIDForIdent("Abw"));
				//$this->ProgrammAuswahl();
            		//break;
				
        	//}
		
        $this->SetValue($key, $value);	
		
   	}
	
	
	
	public function VariabelStandartaktion(){
		
		$this->EnableAction("Mod");
		
	}
		
		
	
	
	public function Test(){
		
		$this->EnableAction("SW_Abs");
		
		
	}
	
    
		   
    }
?>
