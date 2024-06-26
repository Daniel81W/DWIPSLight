<?php /** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSLightControl extends IPSModule {


		public function Create()
		{
			//Never delete this line!
			parent::Create();

            $this->RegisterPropertyString("Lights", "");
            $this->RegisterVariableInteger("oncount", $this->Translate("num_lights_on"),"",1);

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
            IPS_Sleep(1000);
            $lightson = 0;
            $lights = IPS_GetInstanceListByModuleID("{71C85E1B-BD56-1C5A-1EBF-70CCB6E4523A}");
            foreach ($lights as $light){
                if(GetValue(IPS_GetObjectIDByIdent("on", $light))){
                   $lightson += 1;
                }
            }
            $lights = IPS_GetInstanceListByModuleID("{9622A505-C954-346D-7F85-BD1901EDE263}");
            foreach ($lights as $light){
                if(GetValue(IPS_GetObjectIDByIdent("on", $light))){
                    $lightson += 1;
                }
            }
            $this->SetValue("oncount", $lightson);
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