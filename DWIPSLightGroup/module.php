<?php /** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLightGroup extends IPSModule {


		public function Create()
		{
			//Never delete this line!
			parent::Create();

            $this->RegisterPropertyString("Lights", "");
            $this->RegisterPropertyString("Scenes", "");
            $this->RegisterAttributeString("SceneValues", "");
            $emptyArr = [];
            $this->WriteAttributeString("SceneValues", json_encode($emptyArr));

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

            $lightArray = json_decode($this->ReadPropertyString("Lights"), true);

            //
            $hasOn = @$this->GetIDForIdent("on")>1;
            $hasBrightness = @$this->GetIDForIdent("brightness")>1;
            $hasColor = @$this->GetIDForIdent("color")>1;
            $hasColorTemp = @$this->GetIDForIdent("color_temp")>1;
            foreach ($lightArray as $light) {
                if(!$hasOn){
                    if(@IPS_GetObjectIDByIdent("on", $light["InstanceID"]) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableBoolean("on", $this->Translate("state"),"~Switch",1);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("on");
                        $hasOn = true;
                    }
                }
                if(!$hasBrightness){
                    if(@IPS_GetObjectIDByIdent("brightness", $light["InstanceID"]) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableInteger("brightness", $this->Translate("brightness"), "~Intensity.100", 2);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("brightness");
                        $hasBrightness = true;
                    }
                }

                if(!$hasColor){
                    if(@IPS_GetObjectIDByIdent("color", $light["InstanceID"]) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableInteger("color", $this->Translate("color"), "~HexColor", 3);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("color");
                        $hasColor = true;
                    }
                }
                if(!$hasColorTemp){
                    if(@IPS_GetObjectIDByIdent("color_temp", $light["InstanceID"]) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableInteger("color_temp", $this->Translate("colortemp"), "DWIPS.Light.".$this->Translate("colortemp"), 4);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("color_temp");
                        $hasColorTemp = true;
                    }
                }
            }


            $scenes = json_decode($this->ReadPropertyString("Scenes"), true);

            if(count($scenes) > 0){
                if(!IPS_VariableProfileExists("DWIPS_".$this->Translate("scene")."_".$this->InstanceID)){
                    IPS_CreateVariableProfile("DWIPS_".$this->Translate("scene")."_".$this->InstanceID,3);
                }else{
                    $sceneProfile = IPS_GetVariableProfile("DWIPS_".$this->Translate("scene")."_".$this->InstanceID);
                    $sceneAssocs = $sceneProfile["Associations"];
                    foreach ($sceneAssocs as $assoc){
                        IPS_SetVariableProfileAssociation($sceneProfile["ProfileName"], $assoc["Value"],"","", -1);
                    }
                }
                foreach ($scenes as $scene){
                    $this->SendDebug("",$scene["Name"],0);
                    IPS_SetVariableProfileAssociation("DWIPS_".$this->Translate("scene")."_".$this->InstanceID, $scene["Name"],$scene["Name"],"", -1);
                }

                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableString("scene", $this->Translate("scene"),"DWIPS_".$this->Translate("scene")."_".$this->InstanceID, 5);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("scene");

            }
		}

        public function RequestAction($Ident, $Value){

            $lightArray = json_decode($this->ReadPropertyString("Lights"), true);

            switch ($Ident){
                case "on":
                    foreach ($lightArray as $light) {
                        @RequestAction(@IPS_GetObjectIDByIdent("on", $light["InstanceID"]), $Value);
                    }
                    break;
                case "brightness":
                    foreach ($lightArray as $light) {
                        @RequestAction(@IPS_GetObjectIDByIdent("brightness", $light["InstanceID"]), $Value);
                    }
                    break;
                case "color":
                    foreach ($lightArray as $light) {
                        @RequestAction(@IPS_GetObjectIDByIdent("color", $light["InstanceID"]), $Value);
                    }
                    break;
                case "color_temp":
                    foreach ($lightArray as $light) {
                        @RequestAction(@IPS_GetObjectIDByIdent("color_temp", $light["InstanceID"]), $Value);
                    }
                    break;
                case "scene":
                    break;
                default:
                    throw new Exception("Invalid Ident");
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

        public function StoreScene($SceneName)
        {
            $emptyArr = [];
            $this->WriteAttributeString("SceneValues", json_encode($emptyArr));
            $SceneValues = json_decode($this->ReadAttributeString("SceneValues"),true);

            $this->SendDebug("1", print_r($SceneValues, true), 0);
            $scene = [];
            $lightArray = json_decode($this->ReadPropertyString("Lights"), true);

            foreach ($lightArray as $light){
                $vars = ["on", "brightness", "color", "color_temp"];
                $arr = [];
                foreach($vars as $var){
                    if(@IPS_GetObjectIDByIdent($var, $light["InstanceID"]) > 1){
                        $arr[$var] = GetValue(@IPS_GetObjectIDByIdent($var, $light["InstanceID"]));
                    }
                }
                $scene[$light["InstanceID"]] = $arr;
            }
            $SceneValues[$SceneName] = $scene;
            $this->SendDebug("2", print_r($SceneValues, true), 0);
            $this->WriteAttributeString("SceneValues",json_encode($SceneValues));
        }

        public function SceneValues(){
            return $this->ReadAttributeString("SceneValues");
        }
    }
?>