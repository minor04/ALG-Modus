<?

$mod = 1;
$bear = 1;
$prog = 1;
$hz = true;
$md = true;
$zp = false;
$pa = false;

class ALGModus extends IPSModule
	{
		
		public function Create()
		{
			//Never delete this line!
			parent::Create();
						
			if (!IPS_VariableProfileExists("ALG_Modus")) {
			
				IPS_CreateVariableProfile("ALG_Modus", 1); // 0 boolean, 1 int, 2 float, 3 string,
				IPS_SetVariableProfileValues("ALG_Modus", 1, 2, 0);
				IPS_SetVariableProfileDigits("ALG_Modus", 0);
				IPS_SetVariableProfileAssociation("ALG_Modus", 1, "Party", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG_Modus", 2, "Abewesend", "", 0xFFFF00);
			}

			if (!IPS_VariableProfileExists("SWS-Modus")) {
			
				IPS_CreateVariableProfile("SWS-Modus", 1); // 0 boolean, 1 int, 2 float, 3 string,
				IPS_SetVariableProfileValues("SWS-Modus", 1, 2, 0);
				IPS_SetVariableProfileDigits("SWS-Modus", 0);
				IPS_SetVariableProfileAssociation("SWS-Modus", 1, "Auto", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("SWS-Modus", 2, "Hand", "", 0xFFFFFF);
			}
			
			if (!IPS_VariableProfileExists("ALG_Programm")) {
			
				IPS_CreateVariableProfile("ALG_Programm", 1); // 0 boolean, 1 int, 2 float, 3 string,
				IPS_SetVariableProfileValues("ALG_Programm", 1, 3, 0);
				IPS_SetVariableProfileDigits("ALG_Programm", 0);
				IPS_SetVariableProfileAssociation("ALG_Programm", 1, "Anwesend", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG_Programm", 2, "Party", "", 0xFFFFFF);
				IPS_SetVariableProfileAssociation("ALG_Programm", 3, "Abwesend", "", 0xFFFFFF);
			}
			
			if (!IPS_VariableProfileExists("ALG_Akt")) {
			
				IPS_CreateVariableProfile("ALG_Akt", 0); // 0 boolean, 1 int, 2 float, 3 string,
				IPS_SetVariableProfileIcon("ALG_Akt",  "Switch");
				IPS_SetVariableProfileAssociation("ALG_Akt", false, "Deaktiv", "", 0x00FF00);
				IPS_SetVariableProfileAssociation("ALG_Akt", true, "Aktiv", "", 0x00FF00);
			}
		
			
			//___In_IPS_zurverfügungstehende_Variabeln_______________________________________________
			$this->RegisterVariableInteger("Mod", "Modus", "ALG_Modus", 1);
			$this->RegisterVariableInteger("BeAr", "Betriebsart", "SWS-Modus", 2);
			$this->RegisterVariableInteger("Prog", "Programm", "ALG_Programm", 3);
			$this->RegisterVariableBoolean("HZ", "- Heizung", "ALG_Akt", 5);
			$this->RegisterVariableBoolean("MD", "- Meldung", "ALG_Akt", 6);
			$this->RegisterVariableBoolean("ZP", "AutoZSP", "~Switch", 7);
			$this->RegisterVariableBoolean("Pa", "Party", "~Switch", 8);
			
			//___Modulvariabeln______________________________________________________________________
			$this->RegisterPropertyInteger("AlBWM_01", 0);
			$this->RegisterPropertyInteger("AlBWM_02", 0);
			$this->RegisterPropertyInteger("AlBWM_03", 0);
			$this->RegisterPropertyInteger("AlBWM_04", 0);
			$this->RegisterPropertyInteger("AlBWM_05", 0);
			$this->RegisterPropertyInteger("TrigZP", 0);
			
			$this->RegisterPropertyInteger("ALG_HE", 0);
			$this->RegisterPropertyBoolean("OpHei", true);
			$this->RegisterPropertyBoolean("OpMeld", true);
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
			$this->VariabelOption();

			
        	}
	
	        public function MessageSink ($TimeStamp, $SenderID, $Message, $Data) {
		global $mod, $bear, $prog, $hz, $md, $zp;
            		$triggerAlBWM_01 = $this->ReadPropertyInteger("AlBWM_01");
            		$triggerAlBWM_02 = $this->ReadPropertyInteger("AlBWM_02");
            		$triggerAlBWM_03 = $this->ReadPropertyInteger("AlBWM_03");
            		$triggerAlBWM_04 = $this->ReadPropertyInteger("AlBWM_04");
            		$triggerAlBWM_05 = $this->ReadPropertyInteger("AlBWM_05");
			$triggerZP = $this->ReadPropertyInteger("TrigZP");			
			
			if (($SenderID == $triggerAlBWM_01 or $triggerAlBWM_02) && ($Message == 10603)){// && (boolval($Data[0]))){
				//$prog = getValue($this->GetIDForIdent("prog"));
				//$sw = getValue($this->GetIDForIdent("SW"));
				//$sw_abs = getValue($this->GetIDForIdent("SW_Abs"));
				$this->Auto();
           		}
			
			if (($SenderID == $triggerZP) && ($Message == 10603)){// && (boolval($Data[0]))){
				//$mod = getValue($this->GetIDForIdent("Mod"));
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$pa = getValue($this->GetIDForIdent("Pa"));
				$this->Auto();
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
		global $mod, $bear, $prog, $hz, $md, $zp ,$pa;
        	switch ($key) {
        		//case 'Mod':
				//$mod = $value;
        		case 'BeAr':
				$bear = $value;
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$pa = getValue($this->GetIDForIdent("Pa"));
				$this->ALGAuswahl();
            		break;
				
        		case 'Prog':
				//$mod = getValue($this->GetIDForIdent("Mod"));
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = $value;
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$pa = getValue($this->GetIDForIdent("Pa"));
				$this->ALGAuswahl();
            		break;
				
			case 'HZ':
				//$mod = getValue($this->GetIDForIdent("Mod"));
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = $value;
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$this->ALGAuswahl();
            		break;
				
			case 'MD':
				//$mod = getValue($this->GetIDForIdent("Mod"));
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = $value;
				$zp = getValue($this->GetIDForIdent("ZP"));
				$this->ALGAuswahl();
            		break;
				
        	}
		
        $this->SetValue($key, $value);	
		
   	}
	
	
	
	public function VariabelStandartaktion(){
		
		$this->EnableAction("BeAr");
		$this->EnableAction("Prog");
		$this->EnableAction("MD");
		$this->EnableAction("HZ");
		
		IPS_SetHidden($this->GetIDForIdent("ZP"), true);
		IPS_SetHidden($this->GetIDForIdent("Pa"), true);
		
	}
	

	public function VariabelOption(){
		
		if ($this->ReadPropertyBoolean("OpHei")){
			IPS_SetHidden($this->GetIDForIdent("HZ"), false);
		}
		else{
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
		}
		
		if ($this->ReadPropertyBoolean("OpMeld")){
			IPS_SetHidden($this->GetIDForIdent("MD"), false);
		}
		else{
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
		}
	}
		
	
	public function ZeitPro(){
			
		$KategorieID_Zentral = IPS_GetCategoryIDByName("Zentral", 0);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Zentral);
				
		$EreignisID_von =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_von, "Von");
		IPS_SetParent($EreignisID_von, $InstanzID);
		IPS_SetPosition($EreignisID_von, 10);
		IPS_SetEventCyclic($EreignisID_von, 1 /* Täglich */ ,5,0,0,0,0);
		IPS_SetEventActive($EreignisID_von, true);
		
		$EreignisID_bis =IPS_CreateEvent(1);
		IPS_SetName($EreignisID_bis, "Bis");
		IPS_SetParent($EreignisID_bis, $InstanzID);
		IPS_SetPosition($EreignisID_bis, 11);
		IPS_SetEventCyclic($EreignisID_bis, 1 /* Täglich */ ,5,0,0,0,0);
		boolean IPS_SetEventScript 	($EreignisID_bis, $KategorieID_Zentral = IPS_GetCategoryIDByName("Zentral", 0);
						$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Zentral);
						$VariabelID = IPS_GetVariableIDByName("AutoZSP", $InstanzID);
						SetValue($VariabelID, true););
		IPS_SetEventActive($EreignisID_bis, true);
	}
	
	public function ALGAuswahl(){
		
	global $mod, $bear, $prog, $hz, $md, $zp ,$pa;
		
		$KategorieID_Zentral = IPS_GetCategoryIDByName("Zentral", 0);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Zentral);
		$VariabelID_Ab = IPS_GetEventIDByName("Von", $InstanzID);
		$VariabelID_An = IPS_GetEventIDByName("Bis", $InstanzID);
		
		//__Party
		if($prog == 2){
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			
			//___Auto
			if($bear == 1){
				IPS_SetHidden($VariabelID_Ab, false);
				IPS_SetHidden($VariabelID_An, false);
			}
			
			//___Ein
			if($bear == 2){
				SetValue($this->GetIDForIdent("Pa"), true);
				SetValue($this->GetIDForIdent("Mod"), 1);
				IPS_SetHidden($this->GetIDForIdent("Mod"), false);
			}

			
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
		}
		
		//__Abwesend
		else if($prog == 3){

			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			$this->VariabelOption();
			
			//___Auto
			if($bear == 1){
				IPS_SetHidden($VariabelID_Ab, false);
				IPS_SetHidden($VariabelID_An, false);
				
				if($hz == true && $zp == true){
					SetValue($this->ReadPropertyInteger("ALG_HE"), true);
					SetValue($this->GetIDForIdent("Mod"), 2);
					IPS_SetHidden($this->GetIDForIdent("Mod"), false);
				}
				else{
					SetValue($this->ReadPropertyInteger("ALG_HE"), false);
					IPS_SetHidden($this->GetIDForIdent("Mod"), true);
				}
			}
	
			//___Ein
			if($bear == 2){
				if($hz == true){
					SetValue($this->ReadPropertyInteger("ALG_HE"), true);
					SetValue($this->GetIDForIdent("Mod"), 2);
					IPS_SetHidden($this->GetIDForIdent("Mod"), false);
				}
				else{
					SetValue($this->ReadPropertyInteger("ALG_HE"), false);
					IPS_SetHidden($this->GetIDForIdent("Mod"), true);
				}
			}

		}

		else{
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			
			SetValue($this->GetIDForIdent("ZP"), false);
			SetValue($this->GetIDForIdent("Pa"), false);
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
		}
		
	}
		
	public function Auto(){
		
		global $mod, $bear, $prog, $hz, $md, $zp ,$pa;	
		
		$KategorieID_Zentral = IPS_GetCategoryIDByName("Zentral", 0);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Zentral);
		$VariabelID_Ab = IPS_GetEventIDByName("Von", $InstanzID);
		$VariabelID_An = IPS_GetEventIDByName("Bis", $InstanzID);
		
		if($zp == true){
			if($prog == 2){
				SetValue($this->GetIDForIdent("Pa"), true);
				SetValue($this->GetIDForIdent("Mod"), 1);
				IPS_SetHidden($this->GetIDForIdent("Mod"), false);
			}
			if($prog == 3 && $hz == true){
				SetValue($this->ReadPropertyInteger("ALG_HE"), true);
				SetValue($this->GetIDForIdent("Mod"), 2);
				IPS_SetHidden($this->GetIDForIdent("Mod"), false);
			}
			if($prog == 3 && $md == true){
				$this->Meldung();
			}
		}
		else{
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			SetValue($this->GetIDForIdent("Prog"), 1);
			SetValue($this->GetIDForIdent("Pa"), false);
			SetValue($this->GetIDForIdent("Mod"), 1);
			IPS_SetHidden($this->GetIDForIdent("Mod"), true);
		}
		
	}
		
	
	public function Meldung(){
		
		//$KategorieID_Settings = IPS_GetCategoryIDByName("Konfigurator Instanzen", 0);
		//$InstanzID = IPS_GetInstanceIDByName("WebFront", 0);
			
		//WFC_PushNotification(13905, 'Warnung', 'Test', '', 0);
		WFC_PushNotification(42837, 'Warnung', 'Heey Du Stinker', '', 0);
		//WFC_PushNotification($InstanzID, 'Warnung', 'Test', '', 0);
		
		
	}
	
		    
		   
    }
?>
