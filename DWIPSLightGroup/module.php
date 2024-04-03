<?php /** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLightGroup extends IPSModule {


		public function Create()
		{
			//Never delete this line!
			parent::Create();

            $this->RegisterPropertyString("Lights", "");

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

            //TODO Array mit LichtIDs laden
            $lightIDArray = [1,2];

            //
            $hasOn = @$this->GetIDForIdent("on")>1;
            $hasBrightness = @$this->GetIDForIdent("brightness")>1;
            $hasColor = @$this->GetIDForIdent("color")>1;
            $hasColorTemp = @$this->GetIDForIdent("color_temp")>1;
            foreach ($lightIDArray as $lightID) {
                if(!$hasOn){
                    if(@IPS_GetObjectIDByIdent("on", $lightID) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableBoolean("on", $this->Translate("state"),"~Switch",1);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("on");
                        $hasOn = true;
                    }
                }
                if(!$hasBrightness){
                    if(@IPS_GetObjectIDByIdent("brightness", $lightID) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableInteger("brightness", $this->Translate("brightness"), "~Intensity.100", 2);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("brightness");
                        $hasBrightness = true;
                    }
                }

                if(!$hasColor){
                    if(@IPS_GetObjectIDByIdent("color", $lightID) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableInteger("color", $this->Translate("color"), "~HexColor", 3);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("color");
                        $hasColor = true;
                    }
                }
                if(!$hasColorTemp){
                    if(@IPS_GetObjectIDByIdent("color_temp", $lightID) >1){
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->RegisterVariableInteger("color_temp", $this->Translate("colortemp"), "DWIPS.Light.".$this->Translate("colortemp"), 4);
                        /** @noinspection PhpExpressionResultUnusedInspection */
                        $this->EnableAction("color_temp");
                        $hasColorTemp = true;
                    }
                }
            }

            //TODO Szenenarray laden
            $scenes = ["1","2"];

            if(count($scenes) > 0){
                if(!IPS_VariableProfileExists("DWIPS_".$this->Translate("scene")."_".$this->InstanceID)){
                    IPS_CreateVariableProfile("DWIPS_".$this->Translate("scene")."_".$this->InstanceID,3);
                }else{

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

        public function RegisterLight($lightId){
/*
            $lightArrString = $this->ReadPropertyString("Lights");
            $lightArr = json_decode($lightArrString);

            $newLights = [];
            foreach ($lightArr as $light) {
                array_push($newLights, ["InstanceID" => ])
                $newLights[] = [
                        'info1' => $info['info1'],
                        'info2' => $info['info2'],
                        'info' => $info['info1'] . $info['info2'],
                        'active' => $info['active']
                    ];
                }
            array_push($newLights,["InstanceID" => $lightId]);
            IPS_SetProperty($this->InstanceID, "Lights", json_encode($newLights));
            //$this->UpdateFormField('Lights', 'values', json_encode($newLights));
*/

            if(@IPS_GetObjectIDByIdent("on", $lightId)>0){
                $this->RegisterMessage(IPS_GetObjectIDByIdent("on", $lightId), 10603);
            }
        }

    }
?>