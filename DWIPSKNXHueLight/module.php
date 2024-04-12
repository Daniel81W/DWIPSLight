<?php /** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpRedundantMethodOverrideInspection */
/** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSKNXHueLight extends IPSModule {

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


            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXinActoreaID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXieaID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXdimvalueID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXcolorID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXcolortempID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXsceneID", 0);

            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXoutActoreaID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
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
                IPS_CreateVariableProfile($colortempprofilename, VARIABLETYPE_INTEGER);
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
            $hasKNXScene = false;
            $hasHueEA = false;
            $hasHueDim = false;
            $hasHueColor = false;
            $hasHueColorTemp = false;
            $hasHueScene = false;
            if($huelightid > 1) {
                $hasHueEA = (@IPS_GetObjectIDByIdent("on", $huelightid) > 1);
                $hasHueDim = (@IPS_GetObjectIDByIdent("brightness", $huelightid) > 1);
                $hasHueColor = (@IPS_GetObjectIDByIdent("color", $huelightid) > 1);
                $hasHueColorTemp = (@IPS_GetObjectIDByIdent("color_temperature", $huelightid) > 1);
                $hasHueScene = (@IPS_GetObjectIDByIdent("scene", $huelightid) > 1);
            }

            if($hasKNXEA || $hasHueEA){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableBoolean("on", $this->Translate("state"), "~Switch", 1);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("on");
                if($hasKNXEA){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXieaID")), VM_UPDATE);
                }
                if($hasHueEA){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("on", $huelightid), VM_UPDATE);
                }
            }else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->UnregisterVariable("on");
            }

            if($hasKNXDim || $hasHueDim){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableInteger("brightness", $this->Translate("brightness"), "~Intensity.100", 2);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("brightness");
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
                $this->RegisterVariableInteger("color", $this->Translate("color"), "~HexColor", 3);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("color");
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
                $this->RegisterVariableInteger("color_temp", $this->Translate("colortemp"), "DWIPS.Light.".$this->Translate("colortemp"), 4);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("color_temp");
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

            if($hasKNXScene || $hasHueScene){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableString("scene", $this->Translate("scene"), IPS_GetVariable(IPS_GetObjectIDByIdent("scene", $huelightid))['VariableProfile'], 5);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("scene");
                if($hasKNXScene){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXsceneID")), VM_UPDATE);
                }
                if($hasHueScene){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("scene", $huelightid), VM_UPDATE);
                }
            }else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->UnregisterVariable("scene");
            }


            if(count(IPS_GetInstanceListByModuleID("{A3BDFBC5-CDDB-5656-F265-DB4132FEE4B0}")) > 0) {
                /** @noinspection PhpUndefinedFunctionInspection */
                @DWIPSLIGHTCONTROL_RegisterLight(IPS_GetInstanceListByModuleID("{A3BDFBC5-CDDB-5656-F265-DB4132FEE4B0}")[0], $this->InstanceID);
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
            $knxActEAID = $this->ReadPropertyInteger("KNXoutActoreaID");
            $hueID = $this->ReadPropertyInteger("HueLightID");

            //Wenn sendende ID Variable mit Ident "Value" der KNX an/aus DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "on" entsprechend setzen und wenn vorhanden Hue-Status auch entsprechend setzen
	        if($SenderID == IPS_GetObjectIDByIdent("Value",$knxOnID) && $Message == VM_UPDATE){
                $this->SetState($Data[0], $SenderID);
            }
            //Wenn sendende ID Variable mit Ident "Value" der KNX Dim DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "brightness" entsprechend setzen und wenn vorhanden HueBrightness auch entsprechend setzen
            if($SenderID == IPS_GetObjectIDByIdent("Value",$knxBrightnessID) && $Message == VM_UPDATE){
                $this->SetBrightness($Data[0]);
            }
            //Wenn sendende ID Variable mit Ident "Value" der KNX Farb DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "color" entsprechend setzen und wenn vorhanden Hue-Color auch entsprechend setzen
            if($SenderID == IPS_GetObjectIDByIdent("Value0",$knxColorID) && $Message == VM_UPDATE){
                $this->SetColor($Data[0]);
            }
            //Wenn sendende ID Variable mit Ident "Value" der KNX Farbtemp DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "color_temp" entsprechend setzen und wenn vorhanden Hue-colortemp auch entsprechend setzen
            if($SenderID == IPS_GetObjectIDByIdent("Value",$knxColorTemperatureID) && $Message == VM_UPDATE){
                $this->SetColorTemperature($Data[0]);
            }

            if($SenderID == IPS_GetObjectIDByIdent("on",$hueID) && $Message == VM_UPDATE){
                $this->SetValue("on", $Data[0] && (GetValue(IPS_GetObjectIDByIdent("Value", $knxOnID)) || GetValue(IPS_GetObjectIDByIdent("Value", $knxActEAID))));
            }
            if($SenderID == IPS_GetObjectIDByIdent("brightness",$hueID) && $Message == VM_UPDATE){
                $this->SetValue("brightness", $Data[0]);
            }
            if($SenderID == IPS_GetObjectIDByIdent("color",$hueID) && $Message == VM_UPDATE){
                $this->SetValue("color", $Data[0]);
            }
            if($SenderID == IPS_GetObjectIDByIdent("color_temperature",$hueID) && $Message == VM_UPDATE){
                $this->SetValue("color_temp", $this->MiredToKelvin($Data[0]));
            }

			
		}

        public function RequestAction($Ident, $Value) {

            switch($Ident) {
                case "on":
                    $this->SetState($Value, 0);
                    break;
                case "brightness":
                    $this->SetBrightness($Value);
                    break;
                case "color":
                    $this->SetColor($Value);
                    break;
                case "color_temp":
                    $this->SetColorTemperature($Value);
                    break;
                case "scene":
                    $this->SetScene($Value);
                    break;
                default:
                    throw new Exception("Invalid Ident");
            }

        }

        public function SetState($State, $SenderID){
            $this->SetValue("on", $State);
            if($State) {
                if($this->ReadPropertyInteger("KNXoutActoreaID")>1){
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXoutActoreaID"), $State);
                }
                IPS_Sleep(800);
                if($this->ReadPropertyInteger("KNXouteaID") > 1){
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), $State);
                }
                if($this->ReadPropertyInteger("KNXoutActoreaID")<=1 && $SenderID != $this->ReadPropertyInteger("KNXieaID")){
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXieaID"), $State);
                }
                if($this->ReadPropertyInteger("HueLightID") > 1 && IPS_GetObjectIDByIdent("on", $this->ReadPropertyInteger("HueLightID")) >1){
                    RequestAction(IPS_GetObjectIDByIdent("on", $this->ReadPropertyInteger("HueLightID")), $State);
                }
            }else{
                if($this->ReadPropertyInteger("KNXouteaID") > 1){
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), $State);
                }
                if($this->ReadPropertyInteger("KNXoutActoreaID")<=1 && $SenderID != $this->ReadPropertyInteger("KNXieaID")){
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXieaID"), $State);
                }
                if($this->ReadPropertyInteger("HueLightID") > 1 && IPS_GetObjectIDByIdent("on", $this->ReadPropertyInteger("HueLightID")) >1){
                    RequestAction(IPS_GetObjectIDByIdent("on", $this->ReadPropertyInteger("HueLightID")), $State);
                }
                IPS_Sleep(1000);
                if($this->ReadPropertyInteger("KNXoutActoreaID")>1){
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXoutActoreaID"), $State);
                }
            }
        }

        public function SetBrightness($Brightness){
            $this->SetValue("brightness", $Brightness);


            $actorState = false;
            if ($this->ReadPropertyInteger("KNXinActoreaID") > 1){
                $actorState = GetValue(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXinActoreaID")));
            }elseif($this->ReadPropertyInteger("KNXoutActoreaID") > 1){
                $actorState = GetValue(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXoutActoreaID")));
            }

            if($Brightness>0) {
                $this->SetValue("on", true);
                if (!$actorState) {
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXoutActoreaID"), true);
                    IPS_Sleep(1500);
                }
                if ($this->ReadPropertyInteger("KNXouteaID") > 1) {
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), true);
                }
                if ($this->ReadPropertyInteger("KNXoutdimvalueID") > 1) {
                    KNX_WriteDPT5($this->ReadPropertyInteger("KNXoutdimvalueID"), $Brightness);
                }
                if ($this->ReadPropertyInteger("HueLightID") > 1  && IPS_GetObjectIDByIdent("brightness", $this->ReadPropertyInteger("HueLightID")) > 1) {
                    RequestAction(IPS_GetObjectIDByIdent("brightness", $this->ReadPropertyInteger("HueLightID")), $Brightness);
                    IPS_Sleep(1500);
                    if(GetValue(IPS_GetObjectIDByIdent("brightness", $this->ReadPropertyInteger("HueLightID"))) != $Brightness) {
                        RequestAction(IPS_GetObjectIDByIdent("brightness", $this->ReadPropertyInteger("HueLightID")), $Brightness);
                    }
                }

            }else{
                $this->SetValue("on", false);
                if ($this->ReadPropertyInteger("KNXouteaID") > 1) {
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), false);
                }
                if ($this->ReadPropertyInteger("KNXoutdimvalueID") > 1) {
                    KNX_WriteDPT5($this->ReadPropertyInteger("KNXoutdimvalueID"), $Brightness);
                }
                if ($this->ReadPropertyInteger("HueLightID") > 1  && IPS_GetObjectIDByIdent("brightness", $this->ReadPropertyInteger("HueLightID")) > 1) {
                    RequestAction(IPS_GetObjectIDByIdent("brightness", $this->ReadPropertyInteger("HueLightID")), $Brightness);
                }
                if ($actorState) {
                    IPS_Sleep(1500);
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXoutActoreaID"), false);
                }
            }
        }

        public function SetColor($Color){
            $this->SetValue("color", $Color);

            $actorState = false;
            if ($this->ReadPropertyInteger("KNXinActoreaID") > 1){
                $actorState = GetValue(@IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXinActoreaID")));
            }elseif($this->ReadPropertyInteger("KNXoutActoreaID") > 1){
                $actorState = GetValue(@IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXoutActoreaID")));
            }

            if($Color>0) {
                $this->SetValue("on", true);
                if (!$actorState) {
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXoutActoreaID"), true);
                    IPS_Sleep(1500);
                }
                if ($this->ReadPropertyInteger("KNXouteaID") > 1) {
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), true);
                }
                if ($this->ReadPropertyInteger("KNXoutcolorID") > 1) {
                    KNX_WriteDPT232($this->ReadPropertyInteger("KNXoutcolorID"), $this->dec2rgb($Color)["r"],$this->dec2rgb($Color)["g"],$this->dec2rgb($Color)["b"]);//$Color);
                }
                if ($this->ReadPropertyInteger("HueLightID") > 1  && IPS_GetObjectIDByIdent("color", $this->ReadPropertyInteger("HueLightID")) > 1) {
                    RequestAction(IPS_GetObjectIDByIdent("color", $this->ReadPropertyInteger("HueLightID")), $Color);
                    IPS_Sleep(1500);
                    if(GetValue(IPS_GetObjectIDByIdent("color", $this->ReadPropertyInteger("HueLightID"))) != $Color) {
                        RequestAction(IPS_GetObjectIDByIdent("color", $this->ReadPropertyInteger("HueLightID")), $Color);
                    }
                }

            }else{
                $this->SetValue("on", false);
                if ($this->ReadPropertyInteger("KNXouteaID") > 1) {
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), false);
                }
                if ($this->ReadPropertyInteger("KNXoutcolorID") > 1) {
                    KNX_WriteDPT232($this->ReadPropertyInteger("KNXoutcolorID"), 0,0,0);
                }
                if ($this->ReadPropertyInteger("HueLightID") > 1  && IPS_GetObjectIDByIdent("color", $this->ReadPropertyInteger("HueLightID")) > 1) {
                    RequestAction(IPS_GetObjectIDByIdent("color", $this->ReadPropertyInteger("HueLightID")), $Color);
                }
                if ($actorState) {
                    IPS_Sleep(1500);
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXoutActoreaID"), false);
                }
            }
        }

        public function SetColorTemperature($ColorTemperature){
            $this->SetValue("color_temp", $ColorTemperature);

            $actorState = false;
            if ($this->ReadPropertyInteger("KNXinActoreaID") > 1){
                $actorState = GetValue(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXinActoreaID")));
            }elseif($this->ReadPropertyInteger("KNXoutActoreaID") > 1){
                $actorState = GetValue(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXoutActoreaID")));
            }


            $this->SetValue("on", true);
            if (!$actorState) {
                KNX_WriteDPT1($this->ReadPropertyInteger("KNXoutActoreaID"), true);
                IPS_Sleep(1500);
            }
            if ($this->ReadPropertyInteger("KNXouteaID") > 1) {
                KNX_WriteDPT1($this->ReadPropertyInteger("KNXouteaID"), true);
            }
            if ($this->ReadPropertyInteger("KNXoutcolortempID") > 1) {
                KNX_WriteDPT7($this->ReadPropertyInteger("KNXoutcolortempID"), $ColorTemperature);
            }
            if ($this->ReadPropertyInteger("HueLightID") > 1  && IPS_GetObjectIDByIdent("color_temperature", $this->ReadPropertyInteger("HueLightID")) > 1) {
                RequestAction(IPS_GetObjectIDByIdent("color_temperature", $this->ReadPropertyInteger("HueLightID")), $this->KelvinToMired($ColorTemperature));
                IPS_Sleep(1500);
                if(GetValue(IPS_GetObjectIDByIdent("color_temperature", $this->ReadPropertyInteger("HueLightID"))) != $this->KelvinToMired($ColorTemperature)) {
                    RequestAction(IPS_GetObjectIDByIdent("color_temperature", $this->ReadPropertyInteger("HueLightID")), $this->KelvinToMired($ColorTemperature));
                }
            }
        }

        public function SetScene($Scene){

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

        private function dec2rgb($DecColor){
            $r = intdiv($DecColor, 256*256);
            $g = intdiv(($DecColor - $r *256*256),256);
            $b = $DecColor - $r*256*256 - $g *256;
            return ["r" => $r, "g" => $g, "b" => $b];
        }
    }
?>