<?php /** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLight extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            $this->RegisterPropertyBoolean("knxinput", false);
            $this->RegisterPropertyBoolean("knxoutput", false);
            $this->RegisterPropertyBoolean("IsHue", false);


            $this->RegisterPropertyInteger("KNXieaID", 0);
            $this->RegisterPropertyInteger("KNXdimvalueID", 0);
            $this->RegisterPropertyInteger("KNXcolorID", 0);
            $this->RegisterPropertyInteger("KNXcolortempID", 0);

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

            $huelightid = $this->ReadPropertyInteger("HueLightID");
            $hueonid = 0;
            $huebrightnessid = 0;
            $huecolorid = 0;
            $huecolortempid = 0;
            if($huelightid > 0){
                $hueonid = IPS_GetObjectIDByIdent ("on", $huelightid);
                $huebrightnessid = IPS_GetObjectIDByIdent ("brightness", $huelightid);
                $huecolorid = IPS_GetObjectIDByIdent ("color", $huelightid);
                $huecolortempid = IPS_GetObjectIDByIdent ("color_temperature", $huelightid);
            }
            $knxeaid = $this->ReadPropertyInteger("KNXieaID");
            $knxdimid = $this->ReadPropertyInteger("KNXdimvalueID");
            $knxcolorid = $this->ReadPropertyInteger("KNXcolorID");
            $knxcolortempid = $this->ReadPropertyInteger("KNXcolortempID");
            if($knxeaid > 1 ||  $hueonid > 1){
                $this->RegisterVariableBoolean("on", "Status", "~Switch");
            }else{
                $this->UnregisterVariable("on");
            }
            if($knxdimid > 1 ||  $huebrightnessid > 1){
                $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100");
            }
            if($knxcolorid > 1 ||  $huecolorid > 1){
                $this->RegisterVariableInteger("color", "Farbe", "~HexColor");
            }
            if($knxcolortempid > 1 ||  $huecolortempid > 1){
                $this->RegisterVariableInteger("color_temp", "Farbtemperatur", "PhilipsHUE.ColorTemperature");
            }

            /*
            if($this->ReadPropertyBoolean("IsHue")){
                $huelightid = $this->ReadPropertyInteger("HueLightID");
                if($huelightid > 1){
                    //an aus
                    $onid = IPS_GetObjectIDByIdent ("on", $huelightid);
                    if($onid > 1 && IPS_GetObjectIDByIdent("on", $this->InstanceID) == 0){
                        $this->RegisterVariableBoolean("on", "Status", "~Switch");
                    }elseif($onid == 0){
                        $this->UnregisterVariable("on");
                    }
                    //Helligkeit
                    $brightnessid = IPS_GetObjectIDByIdent ("brightness", $huelightid);
                    if($brightnessid > 1){
                        $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100");
                    }else{
                        $this->UnregisterVariable("brightness");
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
            }*/
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