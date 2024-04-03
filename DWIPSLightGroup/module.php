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