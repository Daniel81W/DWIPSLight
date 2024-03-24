<?php /** @noinspection PhpRedundantClosingTagInspection */

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
            $lights = IPS_GetInstanceListByModuleID("{71C85E1B-BD56-1C5A-1EBF-70CCB6E4523A}");
            $lightson = 0;
            foreach ($lights as $light){
                if(GetValue(IPS_GetObjectIDByIdent("on", $light))){
                   $lightson += 1;
                }
            }
            $this->SetValue("oncount", $lightson);
		}

        public function RegisterLight($lightId){
            $this->RegisterMessage(IPS_GetObjectIDByIdent("on", $lightId), 10603);
        }

    }
?>