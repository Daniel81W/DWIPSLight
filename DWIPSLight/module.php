<?php /** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLight extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            $this->RegisterPropertyBoolean("IsHue", false);
            $this->RegisterPropertyInteger("HueLightID", 0);
            $this->RegisterPropertyInteger("HueConnID", 0);
			
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

            if($this->ReadPropertyBoolean("IsHue")){
                $huelightid = $this->ReadPropertyInteger("HueLightID");
                if($huelightid > 1){
                    //an aus
                    $onid = IPS_GetObjectIDByIdent ("on", $huelightid);
                    if($onid > 1 && IPS_GetObjectIDByIdent("on", $this->InstanceID) == 0){
                        $this->RegisterVariableInteger("on", "Status", "~Switch");
                    }elseif($onid == 0){
                        $this->UnregisterVariable("on");
                    }
                    //Farbe
                    $colorid = IPS_GetObjectIDByIdent ("color", $huelightid);
                    if($colorid > 1){
                        $this->RegisterVariableInteger("color", "Farbe", "~HexColor");
                    }else{
                        $this->UnregisterVariable("color");
                    }
                    //Farbtemp
                    @$colortempid = IPS_GetObjectIDByIdent ("color_temperature", $huelightid);
                    if($colortempid > 1){
                        $this->RegisterVariableInteger("color_temp", "Farbtemperatur", "PhilipsHUE.ColorTemperature");
                    }else{
                        $this->UnregisterVariable("color_temp");
                    }
                }
            }
		}

		/**
        * Die folgenden Funktionen stehen automatisch zur Verfügung, wenn das Modul über die "Module Control" eingefügt wurden.
        * Die Funktionen werden, mit dem selbst eingerichteten Prefix, in PHP und JSON-RPC wie folgt zur Verfügung gestellt:
        *
        * DWIPSShutter_UpdateSunrise($id);
        *
        */

		public function ReceiveData($JSONString) {

		}

		public function MessageSink($TimeStamp, $SenderID, $Message, $Data) {
	

			
		}
		
	}
?>