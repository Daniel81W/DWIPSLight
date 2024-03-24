<?php /** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLight extends IPSModule {

        private $OnID = 0;
        private $BrightnessID = 0;
        private $ColorID = 0;
        private $ColorTemperatureID = 0;
        private $KNXOnID = 0;
        private $KNXBrightnessID = 0;
        private $KNXColorID = 0;
        private $KNXColorTemperatureID = 0;
        private $HueOnID = 0;
        private $HueBrightnessID = 0;
        private $HueColorID = 0;
        private $HueColorTemperatureID = 0;

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
            if($huelightid > 0){
                $this->HueOnID = IPS_GetObjectIDByIdent ("on", $huelightid);
                $this->HueBrightnessID = IPS_GetObjectIDByIdent ("brightness", $huelightid);
                $this->HueColorID = IPS_GetObjectIDByIdent ("color", $huelightid);
                $this->HueColorTemperatureID = IPS_GetObjectIDByIdent ("color_temperature", $huelightid);
            }
            $this->KNXOnID = $this->ReadPropertyInteger("KNXieaID");
            if($this->KNXOnID > 1){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXOnID), 10603);
            }
            else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                //$this->UnregisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXOnID), 10603);
            }
            $this->KNXBrightnessID = $this->ReadPropertyInteger("KNXdimvalueID");
            if($this->KNXBrightnessID > 1){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXBrightnessID), 10603);
            }
            else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                //$this->UnregisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXBrightnessID), 10603);
            }
            $this->KNXColorID = $this->ReadPropertyInteger("KNXcolorID");
            if($this->KNXColorID > 1){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXColorID), 10603);
            }
            else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                //$this->UnregisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXColorID), 10603);
            }
            $this->KNXColorTemperatureID = $this->ReadPropertyInteger("KNXcolortempID");
            if($this->KNXColorTemperatureID > 1){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXColorTemperatureID), 10603);
            }
            else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                //$this->UnregisterMessage(IPS_GetObjectIDByIdent("Value", $this->KNXColorTemperatureID), 10603);
            }
            if($this->KNXOnID > 1 ||  $this->HueOnID > 1){
                $this->OnID = $this->RegisterVariableBoolean("on", "Status", "~Switch");
            }else{
                if($this->UnregisterVariable("on")){
                    $this->OnID = 0;
                }
            }
            if($this->KNXBrightnessID > 1 ||  $this->HueBrightnessID > 1){
                $this->BrightnessID = $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100");
            }else{
                if($this->UnregisterVariable("brightness")){
                    $this->BrightnessID = 0;
                }
            }
            if($this->KNXColorID > 1 ||  $this->HueColorID > 1){
                $this->ColorID = $this->RegisterVariableInteger("color", "Farbe", "~HexColor");
            }else{
                if($this->UnregisterVariable("color")){
                    $this->ColorID = 0;
                }
            }
            if($this->KNXColorTemperatureID > 1 ||  $this->HueColorTemperatureID > 1){
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
            $this->KNXOnID = $this->ReadPropertyInteger("KNXieaID");

	        if($SenderID == IPS_GetObjectIDByIdent("Value",$this->KNXOnID) && $Message == 10603){
                $this->SendDebug("KNX", $Data[0],0);
                $this->SendDebug("KNX", $Data[1],0);
                $this->SendDebug("KNX", $Data[2],0);
            }

			
		}
		
	}
?>