<?php /** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLight extends IPSModule {

        private $OnID = 0;
        private $BrightnessID = 0;
        private $ColorID = 0;
        private $ColorTemperatureID = 0;

		public function Create()
		{
			//Never delete this line!
			parent::Create();

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("knxinput", false);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("knxoutput", false);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyBoolean("IsHue", false);
            /** @noinspection PhpExpressionResultUnusedInspection */


            $this->RegisterPropertyInteger("KNXieaID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXdimvalueID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXcolorID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXcolortempID", 0);

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("HueLightID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
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
            if($knxeaid > 1){$this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $knxeaid), 10603);}
            else{$this->UnregisterMessage(IPS_GetObjectIDByIdent("Value", $knxeaid), 10603);}
            $knxdimid = $this->ReadPropertyInteger("KNXdimvalueID");
            $knxcolorid = $this->ReadPropertyInteger("KNXcolorID");
            $knxcolortempid = $this->ReadPropertyInteger("KNXcolortempID");
            if($knxeaid > 1 ||  $hueonid > 1){
                $this->OnID = $this->RegisterVariableBoolean("on", "Status", "~Switch");
            }else{
                if($this->UnregisterVariable("on")){
                    $this->OnID = 0;
                }
            }
            if($knxdimid > 1 ||  $huebrightnessid > 1){
                $this->BrightnessID = $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100");
            }else{
                if($this->UnregisterVariable("brightness")){
                    $this->BrightnessID = 0;
                }
            }
            if($knxcolorid > 1 ||  $huecolorid > 1){
                $this->ColorID = $this->RegisterVariableInteger("color", "Farbe", "~HexColor");
            }else{
                if($this->UnregisterVariable("color")){
                    $this->ColorID = 0;
                }
            }
            if($knxcolortempid > 1 ||  $huecolortempid > 1){
                $this->ColorTemperatureID = $this->RegisterVariableInteger("color_temp", "Farbtemperatur", "PhilipsHUE.ColorTemperature");
            }else{
                if($this->UnregisterVariable("color_temp")){
                    $this->ColorTemperatureID = 0;
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
	        $this->SendDebug("KNX", $SenderID,0);

			
		}
		
	}
?>