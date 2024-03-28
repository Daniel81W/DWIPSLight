<?php /** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpRedundantMethodOverrideInspection */
/** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLight extends IPSModule {

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

            $this->RegisterPropertyInteger("KNXouteaID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXoutdimvalueID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXoutcolorID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXoutcolortempID", 0);

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("HueLightID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("HueConnID", 0);


            $colortempprofilename = "DWIPS.Light.".$this->Translate("colortemp");
            if (!IPS_VariableProfileExists($colortempprofilename)){
                IPS_CreateVariableProfile($colortempprofilename, 1);
                IPS_SetVariableProfileValues($colortempprofilename,2000, 6536, 1);
                IPS_SetVariableProfileText($colortempprofilename, "", " K");
            }
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

            $hasKNXEA = ($this->ReadPropertyInteger("KNXieaID") > 1);
            $hasKNXDim = ($this->ReadPropertyInteger("KNXdimvalueID") > 1);
            $hasKNXColor = ($this->ReadPropertyInteger("KNXcolorID") > 1);
            $hasKNXColorTemp = ($this->ReadPropertyInteger("KNXcolortempID") > 1);
            $hasHueEA = false;
            $hasHueDim = false;
            $hasHueColor = false;
            $hasHueColorTemp = false;
            if($huelightid > 1) {
                $hasHueEA = (IPS_GetObjectIDByIdent("on", $huelightid) > 1);
                $hasHueDim = (IPS_GetObjectIDByIdent("brightness", $huelightid) > 1);
                $hasHueColor = (IPS_GetObjectIDByIdent("color", $huelightid) > 1);
                $hasHueColorTemp = (IPS_GetObjectIDByIdent("color_temperature", $huelightid) > 1);
            }

            if($hasKNXEA || $hasHueEA){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableBoolean("on", "Status", "~Switch", 1);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("on");
                if($hasKNXEA){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXieaID")), 10603);
                }
                if($hasHueEA){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("on", $huelightid), 10603);
                }
            }else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->UnregisterVariable("on");
            }

            if($hasKNXDim || $hasHueDim){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100", 2);
                if($hasKNXDim){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXdimvalueID")), 10603);
                }
                if($hasHueDim){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("brightness", $huelightid), 10603);
                }
            }else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->UnregisterVariable("brightness");
            }

            if($hasKNXColor || $hasHueColor){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableInteger("color", "Farbe", "~HexColor", 3);
                if($hasKNXColor){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("Value0", $this->ReadPropertyInteger("KNXcolorID")), 10603);
                }
                if($hasHueColor){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("color", $huelightid), 10603);
                }
            }else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->UnregisterVariable("color");
            }

            if($hasKNXColorTemp || $hasHueColorTemp){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableInteger("color_temp", "Farbtemperatur", "DWIPS.Light.".$this->Translate("colortemp"), 4);
                if($hasKNXColorTemp){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXcolortempID")), 10603);
                }
                if($hasHueColorTemp){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("color_temperature", $huelightid), 10603);
                }
            }else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->UnregisterVariable("color_temp");
            }


            if(count(IPS_GetInstanceListByModuleID("{A3BDFBC5-CDDB-5656-F265-DB4132FEE4B0}")) > 0) {
                /** @noinspection PhpUndefinedFunctionInspection */
                DWIPSLightControl_RegisterLight(IPS_GetInstanceListByModuleID("{A3BDFBC5-CDDB-5656-F265-DB4132FEE4B0}")[0], $this->InstanceID);
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
            //IDs aus Properties auslesen
            $knxOnID = $this->ReadPropertyInteger("KNXieaID");
            $knxBrightnessID = $this->ReadPropertyInteger("KNXdimvalueID");
            $knxColorID = $this->ReadPropertyInteger("KNXcolorID");
            $knxColorTemperatureID = $this->ReadPropertyInteger("KNXcolortempID");
            $hueOnID = IPS_GetObjectIDByIdent ("on", $this->ReadPropertyInteger("HueLightID"));

            //Wenn sendende ID Variable mit Ident "Value" der KNX an/aus DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "on" entsprechend setzen und wenn vorhanden Hue-Status auch entsprechend setzen
	        if($SenderID == IPS_GetObjectIDByIdent("Value",$knxOnID) && $Message == 10603){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->SetValue("on", $Data[0]);
                /** @noinspection PhpUndefinedFunctionInspection */
                PHUE_SwitchMode($this->ReadPropertyInteger("HueLightID"), $Data[0]);
                //($this->ReadPropertyInteger("HueLightID"),"on", $Data[0]);
            }
            //Wenn sendende ID Variable mit Ident "Value" der KNX Dim DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "brightness" entsprechend setzen und wenn vorhanden HueBrightness auch entsprechend setzen
            if($SenderID == IPS_GetObjectIDByIdent("Value",$knxBrightnessID) && $Message == 10603){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->SetValue("brightness", $Data[0]);
            }
            //Wenn sendende ID Variable mit Ident "Value" der KNX Farb DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "color" entsprechend setzen und wenn vorhanden Hue-Color auch entsprechend setzen
            if($SenderID == IPS_GetObjectIDByIdent("Value0",$knxColorID) && $Message == 10603){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->SetValue("color", $Data[0]);
            }
            //Wenn sendende ID Variable mit Ident "Value" der KNX Farbtemp DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "color_temp" entsprechend setzen und wenn vorhanden Hue-colortemp auch entsprechend setzen
            if($SenderID == IPS_GetObjectIDByIdent("Value",$knxColorTemperatureID) && $Message == 10603){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->SetValue("color_temp", $Data[0]);
            }

			
		}

        public function RequestAction($Ident, $Value) {

            switch($Ident) {
                case "on":
                    //Hier würde normalerweise eine Aktion z.B. das Schalten ausgeführt werden
                    //Ausgaben über 'echo' werden an die Visualisierung zurückgeleitet

                    //Neuen Wert in die Statusvariable schreiben

                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue($Ident, $Value);
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), $Value);
                    IPS_RequestAction($this->ReadPropertyInteger("HueLightID"),"on", $Value);
                    break;
                case "brightness":
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue($Ident, $Value);
                    break;
                case "color":
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue($Ident, $Value);
                    break;
                case "Color_temp":
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->SetValue($Ident, $Value);
                    break;
                default:
                    throw new Exception("Invalid Ident");
            }

        }

        /**
         * Converts color temperature in mired to color temperature in Kelvin
         * @param int $mired color temperature in mired
         * @return int color temperature in Kelvin
         */
        private function MiredToKelvin(int $mired):int{
            return intval(round(1000000/$mired,0));
        }

        /**
         * Converts color temperature in Kelvin to color temperature in mired
         * @param int $kelvin color temperature in Kelvin
         * @return int color temperature in mired
         */
        private function KelvinToMired(int $kelvin):int{
            return intval(round(1000000/$kelvin,0));
        }

    }
?>