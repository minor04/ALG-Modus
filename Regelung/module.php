<?
$mod = 1;	// Modus
$bear = 1;	// Betriebsart
$prog = 1;	// Programm
$hz = true;	// Option Heizung
$md = true;	// Option Meldung
$as = true;	// Option Anwesenheitssimulation
$zp = false;	// Auto Zeitschaltprogramm
$pa = false;	// Dummy Variabel Party
$trigid = 0;	// Dummy Triger bestimmung

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
			$this->RegisterVariableBoolean("AS", "- Anwesenheits Simulation", "ALG_Akt", 7);
			$this->RegisterVariableBoolean("ZP", "AutoZSP", "~Switch", 8);
			$this->RegisterVariableBoolean("Pa", "Party", "~Switch", 9);
			
			//___Modulvariabeln______________________________________________________________________
			$this->RegisterPropertyString("WebId", 0);
			$this->RegisterPropertyInteger("AlBWM_01", 0);
			$this->RegisterPropertyInteger("AlBWM_02", 0);
			$this->RegisterPropertyInteger("AlBWM_03", 0);
			$this->RegisterPropertyInteger("AlBWM_04", 0);
			$this->RegisterPropertyInteger("AlBWM_05", 0);
			$this->RegisterPropertyString("BZ_AlTrg_01", 0);
			$this->RegisterPropertyString("BZ_AlTrg_02", 0);
			$this->RegisterPropertyString("BZ_AlTrg_03", 0);
			$this->RegisterPropertyString("BZ_AlTrg_04", 0);
			$this->RegisterPropertyString("BZ_AlTrg_05", 0);
			$this->RegisterPropertyInteger("TrigZP", 0);
			
			$this->RegisterPropertyInteger("ALG_HE", 0);
			$this->RegisterPropertyInteger("AS_An", 0);
			$this->RegisterPropertyBoolean("OpHei", true);
			$this->RegisterPropertyBoolean("OpMeld", true);
			$this->RegisterPropertyBoolean("OpAnwSi", true);
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
		global $mod, $bear, $prog, $hz, $md, $as, $zp, $trigid;
            		$triggerAlBWM_01 = $this->ReadPropertyInteger("AlBWM_01");
            		$triggerAlBWM_02 = $this->ReadPropertyInteger("AlBWM_02");
            		$triggerAlBWM_03 = $this->ReadPropertyInteger("AlBWM_03");
            		$triggerAlBWM_04 = $this->ReadPropertyInteger("AlBWM_04");
            		$triggerAlBWM_05 = $this->ReadPropertyInteger("AlBWM_05");
			$triggerZP = $this->ReadPropertyInteger("TrigZP");			
			
			if (($SenderID == $triggerAlBWM_01 or $triggerAlBWM_02 or $SenderID == $triggerAlBWM_03 or $triggerAlBWM_04 or $triggerAlBWM_05) && ($Message == 10603)){// && (boolval($Data[0]))){
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$md = getValue($this->GetIDForIdent("MD"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$as = getValue($this->GetIDForIdent("AS"));
				
				if ($SenderID == $triggerAlBWM_01 && $Message == 10603){
					$trigid = 1;
				}
				if ($SenderID == $triggerAlBWM_02 && $Message == 10603){
					$trigid = 2;
				}
				if ($SenderID == $triggerAlBWM_03 && $Message == 10603){
					$trigid = 3;
				}
				if ($SenderID == $triggerAlBWM_04 && $Message == 10603){
					$trigid = 4;
				}
				if ($SenderID == $triggerAlBWM_05 && $Message == 10603){
					$trigid = 5;
				}
				$this->Meldung();
           		}
			
			if (($SenderID == $triggerZP) && ($Message == 10603)){// && (boolval($Data[0]))){
				//$mod = getValue($this->GetIDForIdent("Mod"));
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$as = getValue($this->GetIDForIdent("AS"));
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
		global $mod, $bear, $prog, $hz, $md, $as, $zp, $trigid;
        	switch ($key) {
        		//case 'Mod':
				//$mod = $value;
        		case 'BeAr':
				$bear = $value;
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$as = getValue($this->GetIDForIdent("AS"));
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
				$as = getValue($this->GetIDForIdent("AS"));			
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
				$as = getValue($this->GetIDForIdent("AS"));			
				$zp = getValue($this->GetIDForIdent("ZP"));
				$this->ALGAuswahl();
            		break;
				
			case 'MD':
				//$mod = getValue($this->GetIDForIdent("Mod"));
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = $value;
				$as = getValue($this->GetIDForIdent("AS"));
				$zp = getValue($this->GetIDForIdent("ZP"));
				$this->ALGAuswahl();
            		break;
			case 'AS':
				//$mod = getValue($this->GetIDForIdent("Mod"));
				$bear = getValue($this->GetIDForIdent("BeAr"));
				$prog = getValue($this->GetIDForIdent("Prog"));
				$hz = getValue($this->GetIDForIdent("HZ"));
				$md = getValue($this->GetIDForIdent("MD"));
				$as = $value;
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
		$this->EnableAction("AS");
		
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
		
		if ($this->ReadPropertyBoolean("OpAnwSi")){
			IPS_SetHidden($this->GetIDForIdent("AS"), false);
		}
		else{
			IPS_SetHidden($this->GetIDForIdent("AS"), true);
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
		IPS_SetEventActive($EreignisID_bis, true);
	}
	
	public function ALGAuswahl(){
		
	global $mod, $bear, $prog, $hz, $md, $as, $zp, $trigid;
		
		$KategorieID_Zentral = IPS_GetCategoryIDByName("Zentral", 0);
		$InstanzID = IPS_GetInstanceIDByName("Modus", $KategorieID_Zentral);
		$VariabelID_Ab = IPS_GetEventIDByName("Von", $InstanzID);
		$VariabelID_An = IPS_GetEventIDByName("Bis", $InstanzID);
		
		//__Party
		if($prog == 2){
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($this->GetIDForIdent("AS"), true);		
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
			
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
				
				//if($hz == true && $zp == true){
					//SetValue($this->ReadPropertyInteger("ALG_HE"), true);
					//SetValue($this->GetIDForIdent("Mod"), 2);
					//IPS_SetHidden($this->GetIDForIdent("Mod"), false);
				//}
				//else{
					//SetValue($this->ReadPropertyInteger("ALG_HE"), false);
					//IPS_SetHidden($this->GetIDForIdent("Mod"), true);
				//}
			}
	
			//___Ein
			if($bear == 2){
				if($hz == true){
					SetValue($this->ReadPropertyInteger("ALG_HE"), true);
				}
				else{
					SetValue($this->ReadPropertyInteger("ALG_HE"), false);
				}
				
				if($as == true){
					SetValue($this->ReadPropertyInteger("AS_An"), true);					
				}
				else{
					SetValue($this->ReadPropertyInteger("AS_An"), false);
				}
				if($hz == true or $md == true or $as == true){
					SetValue($this->GetIDForIdent("Mod"), 2);
					IPS_SetHidden($this->GetIDForIdent("Mod"), false);
				}
				else{
					IPS_SetHidden($this->GetIDForIdent("Mod"), true);
				}
			}
		}
		else{
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($this->GetIDForIdent("AS"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			
			SetValue($this->GetIDForIdent("ZP"), false);
			SetValue($this->GetIDForIdent("Pa"), false);
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
		}
		
	}
		
	public function Auto(){
		
		global $mod, $bear, $prog, $hz, $md, $as, $zp, $trigid;
		
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
			if($prog == 3 && $as == true){
				SetValue($this->ReadPropertyInteger("AS_An"), true);
			}
		}
		else{
			SetValue($this->ReadPropertyInteger("ALG_HE"), false);
			SetValue($this->ReadPropertyInteger("AS_An"), false);
			IPS_SetHidden($this->GetIDForIdent("MD"), true);
			IPS_SetHidden($this->GetIDForIdent("HZ"), true);
			IPS_SetHidden($this->GetIDForIdent("AS"), true);
			IPS_SetHidden($VariabelID_Ab, true);
			IPS_SetHidden($VariabelID_An, true);
			SetValue($this->GetIDForIdent("Prog"), 1);
			SetValue($this->GetIDForIdent("Pa"), false);
			SetValue($this->GetIDForIdent("Mod"), 1);
			IPS_SetHidden($this->GetIDForIdent("Mod"), true);
		}
		
	}
		
	
	public function Meldung(){
		
		global $mod, $bear, $prog, $hz, $md, $zp ,$pa, $trigid;	
		
		//$KategorieID_Settings = IPS_GetCategoryIDByName("Konfigurator Instanzen", 0);
		//$InstanzID = IPS_GetInstanceIDByName("WebFront", 0);
		$webid = $this->ReadPropertyString("WebId");

		if(($prog == 3 && $md == true && $bear == 1 && $zp == true) or ($prog == 3 && $md == true  && $bear == 2)){			
			
			if($trigid == 1){
				$bz_altrg = $this->ReadPropertyString("BZ_AlTrg_01");
				WFC_PushNotification($webid, 'Warnung', $bz_altrg, '', 0);
			}
			if($trigid == 2){
				$bz_altrg = $this->ReadPropertyString("BZ_AlTrg_02");
				WFC_PushNotification($webid, 'Warnung', $bz_altrg, '', 0);
			}
			if($trigid == 3){
				$bz_altrg = $this->ReadPropertyString("BZ_AlTrg_03");
				WFC_PushNotification($webid, 'Warnung', $bz_altrg, '', 0);
			}
			if($trigid == 4){
				$bz_altrg = $this->ReadPropertyString("BZ_AlTrg_04");
				WFC_PushNotification($webid, 'Warnung', $bz_altrg, '', 0);
			}
			if($trigid == 5){
				$bz_altrg = $this->ReadPropertyString("BZ_AlTrg_05");
				WFC_PushNotification($webid, 'Warnung', $bz_altrg, '', 0);
			}
			//WFC_SendPopup(13905, "Warnung", "Test");
			//WFC_SendNotification(42837, "Warnung", "Test");
			//WFC_PushNotification(42837, 'Warnung', 'Test', '', 35556);
		}		
		
	}    
		   
    }
?>
