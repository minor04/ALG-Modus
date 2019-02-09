<?

$mod = 1;
$prog = 1;
$hz = 0;
$md = 0;
$zp = 0;

class ALGModus extends IPSModule
	{
		
		public function Create()
		{
			//Never delete this line!
			parent::Create();
						
			if (!IPS_VariableProfileExists("ALG-Modus")) {
			
				IPS_CreateVariableProfile("ALG-Modus", 1); // 0 boolean, 1 int, 2 float, 3 string,
				IPS_SetVariableProfileValues("ALG-Modus", 1, 2, 0);
				IPS_SetVariableProfileDigits("ALG-Modus", 0);
				IPS_SetVariableProfileAssociation("ALG-Modus", 1, "Hand", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG-Modus", 2, "Auto", "", 0xFFFFFF);
			}
			
			if (!IPS_VariableProfileExists("ALG_Programm")) {
			
				IPS_CreateVariableProfile("ALG_Programm", 1); // 0 boolean, 1 int, 2 float, 3 string,
				IPS_SetVariableProfileValues("ALG_Programm", 1, 3, 0);
				IPS_SetVariableProfileDigits("ALG_Programm", 0);
				IPS_SetVariableProfileAssociation("ALG_Programm", 1, "Anwesend", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG_Programm", 2, "Party", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG_Programm", 3, "Abwesend", "", 0xFFFFFF);
			}
		
			
			//___In_IPS_zurverfügungstehende_Variabeln_______________________________________________
			$this->RegisterVariableInteger("Mod", "Modus", "ALG-Modus", 1);
			$this->RegisterVariableInteger("Prog", "Programm", "ALG_Programm", 2);
			$this->RegisterVariableBoolean("HZ", "Heizung", "~Switch", 5);
			$this->RegisterVariableBoolean("MD", "Meldung", "~Switch", 6);
			$this->RegisterVariableBoolean("ZP", "AutoZSP", "~Switch", 7);
			//$this->RegisterVariableFloat("SW", "Sollwert", "~Temperature.Room", 3);
			//$this->RegisterVariableBoolean("ZP_Conf", "ZP_Confort", "~Switch", 11);
			
			//___Modulvariabeln______________________________________________________________________
			$this->RegisterPropertyInteger("AlBWM_01", 0);
			$this->RegisterPropertyInteger("AlBWM_02", 0);
			$this->RegisterPropertyInteger("AlBWM_03", 0);
			$this->RegisterPropertyInteger("AlBWM_04", 0);
			$this->RegisterPropertyInteger("AlBWM_05", 0);
			$this->RegisterPropertyInteger("TrigZP", 0);
			
			$this->RegisterPropertyInteger("ALG_HE", 0);
			//$this->RegisterPropertyInteger("UpdateWeatherInterval", 30);
			//$this->RegisterPropertyString("APIkey", 0);

			
		}
	
	        public function ApplyChanges() {
            		//Never delete this line!
            		parent::ApplyChanges();
			
				
            		$triggerAlBWM_01 = $this->ReadPropertyInteger("AlBWM_01");
            		$this->RegisterMessage($triggerAlBWM_01, 10603 /* VM_UPDATE */);
			
            		$triggerAlBWM_02 = $this->ReadPropertyInteger("AlBWM_02");
            		$this->RegisterMessage($triggerAlBWM_02, 10603 /* VM_UPDATE */);
			
            		$triggerAlBWM_03 = $this->ReadPropertyInteger("AlBWM_03");
            		$this->RegisterMessage($triggerAlBWM_03, 10603 /* VM_UPDATE */);
			
            		$triggerAlBWM_04 = $this->ReadPropertyInteger("AlBWM_04");
            		$this->RegisterMessage($triggerAlBWM_04, 10603 /* VM_UPDATE */);

			$triggerAlBWM_05 = $this->ReadPropertyInteger("AlBWM_05");
            		$this->RegisterMessage($triggerAlBWM_05, 10603 /* VM_UPDATE */);
			
			$triggerZP = $this->ReadPropertyInteger("TrigZP");
            		$this->RegisterMessage($triggerZP, 10603 /* VM_UPDATE */);
			
			//Standartaktion Aktivieren
			$this->VariabelStandartaktion();
			
        	}
	
	        public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) {
		global $mod, $prog, $hz, $md, $zp;
            		$triggerAlBWM_01 = $this->ReadPropertyInteger("AlBWM_01");
            		$triggerAlBWM_02 = $this->ReadPropertyInteger("AlBWM_02");
            		$triggerAlBWM_03 = $this->ReadPropertyInteger("AlBWM_03");
            		$triggerAlBWM_04 = $this->ReadPropertyInteger("AlBWM_04");
            		$triggerAlBWM_05 = $this->ReadPropertyInteger("AlBWM_05");
			$triggerZP = $this->ReadPropertyInteger("TrigZP");
	
			$triggerMod = $this->ReadPropertyInteger("Mod");
			
			
			if (($SenderID == $triggerAlBWM_01 or $triggerAlBWM_02) && ($Message == 10603)){// && (boolval($Data[0]))){
				//$prog = getValue($this->GetIDForIdent("prog"));
				//$sw = getValue($this->GetIDForIdent("SW"));
				//$sw_abs = getValue($this->GetIDForIdent("SW_Abs"));
				$this->Meldung();
           		}
			
			if (($SenderID == $triggerMod or $triggerZP) && ($Message == 10603)){// && (boolval($Data[0]))){
				$mod = getValue($this->GetIDForIdent("Mod"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$this->ALGAuswahl();
           		}

        }
        /**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wiefolgt zur Verfügung gestellt:
        *
        * ABC_Calculate($id);
        *
        */
	
	public function RequestAction($key, $value){
		global $mod, $prog, $hz, $md, $zp;
        	switch ($key) {
        		case 'Mod':
				$mod = $value;
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$this->ALGAuswahl();
            		break;
				
        		case 'Prog':
				$mod = getValue($this->GetIDForIdent("Mod"));
				$prog = $value;
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				//$abw = getValue($this->GetIDForIdent("Abw"));
				$this->ALGAuswahl();
            		break;
				
			case 'HZ':
				$mod = getValue($this->GetIDForIdent("Mod"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = $value;
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				//$abw = getValue($this->GetIDForIdent("Abw"));
				$this->ALGAuswahl();
            		break;
				
			case 'MD':
				$mod = getValue($this->GetIDForIdent("Mod"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = $value;
				$zp = getValue($this->GetIDForIdent("ZP"));
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
		
		IPS_SetHidden($this->GetIDForIdent("ZP"), true);
		
	}
		
	
	public function ZeitPro(){
			
		$KategorieID_Settings = IPS_GetCategoryIDByName("Settings", 0);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Settings);
				
		$EreignisID_von =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_von, "Von");
		IPS_SetParent($EreignisID_von, $InstanzID);
		IPS_SetPosition($EreignisID_von, 10);
		IPS_SetEventCyclic($EreignisID_von, 1 /* Täglich */ ,5,0,0,0,0);
		
		$EreignisID_bis =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_bis, "Bis");
		IPS_SetParent($EreignisID_bis, $InstanzID);
		IPS_SetPosition($EreignisID_bis, 11);
		IPS_SetEventCyclic($EreignisID_bis, 1 /* Täglich */ ,5,0,0,0,0);
		
	}
	
	public function ALGAuswahl(){
		
	global global $mod, $prog, $hz, $md, $zp;

		$KategorieID_Settings = IPS_GetCategoryIDByName("Settings", 0);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Settings);
		$VariabelID_Ab = IPS_GetEventIDByName("Von", $InstanzID);
		$VariabelID_An = IPS_GetEventIDByName("Bis", $InstanzID);
		
		if($prog == 2){
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			
			
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
			//echo "Aus";
		}
		else if($prog == 3){
			IPS_SetHidden($this->GetIDForIdent("MD"), false);
			IPS_SetHidden($this->GetIDForIdent("HZ"), false);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			
			if($hz == true){
				SetValue($this->ReadPropertyInteger("ALG_HE"), true);
			}
			else{
				SetValue($this->ReadPropertyInteger("ALG_HE"), false);
			}
			//echo "Hand";
		}
		//else if($mod == 4){
			//IPS_SetHidden($this->GetIDForIdent("MD"), false);
			//IPS_SetHidden($this->GetIDForIdent("HZ"), false);
			//IPS_SetHidden($VariabelID_Ab, false);
			//IPS_SetHidden($VariabelID_An, false);
			
			//if($hz == true && $zp == true){
				//SetValue($this->ReadPropertyInteger("ALG_HE"), true);
			//}
			//else{
				//SetValue($this->ReadPropertyInteger("ALG_HE"), false);
				//SetValue($this->GetIDForIdent("Mod"), 1);
			//}
			//echo "Hand";
		//}
		else{
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
		}
		
	}
	
	public function Meldung(){
			
		//Meldung mus gem. Notification erstellt werden
		
	}
	
	public function ALGHeizung(){
	
	global $mod, $hz, $zp;
		
		if($mod == 3 && $hz == true){
			SetValue($this->ReadPropertyInteger("ALG_HE"), true);
		}
		else{
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
		}
		
		if($mod == 4 && $hz == true && $zp = true){
			SetValue($this->ReadPropertyInteger("ALG_HE"), true);
		}
		else{
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
		}

		
	}
		
	
	
	
	
	public function Demo(){
		
		//$this->EnableAction("SW_Abs");
		
		$a = getValue($this->GetIDForIdent("HZ"));
		
		if($a == true){
			SetValue($this->GetIDForIdent("MD"), true);
		}
		else{
			SetValue($this->GetIDForIdent("MD"), false);
		}
		
	}
	
    
		   
    }
?>
