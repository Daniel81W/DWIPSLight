<?php /** @noinspection PhpExpressionResultUnusedInspection */
/** @noinspection PhpRedundantMethodOverrideInspection */
/** @noinspection PhpRedundantClosingTagInspection */

//include_once("/var/lib/symcon/modules/DWIPSLib/libs/astro.php");
	class DWIPSKNXLight extends IPSModule {

		public function Create()
		{
			//Never delete this line!
			parent::Create();


            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXieaID", 0);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterPropertyInteger("KNXdimvalueID", 0);


            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->RegisterVariableBoolean("on", "Status", "~Switch", 1);
            /** @noinspection PhpExpressionResultUnusedInspection */
            $this->EnableAction("on");

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

            $hasKNXea = ($this->ReadPropertyInteger("KNXieaID") > 1);
            $hasKNXDim = ($this->ReadPropertyInteger("KNXdimvalueID") > 1);

            if($hasKNXea) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXieaID")), 10603);
            }

            if($hasKNXDim){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100", 2);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("brightness");
                if($hasKNXDim){
                    /** @noinspection PhpExpressionResultUnusedInspection */
                    $this->RegisterMessage(IPS_GetObjectIDByIdent("Value", $this->ReadPropertyInteger("KNXdimvalueID")), 10603);
                }
            }else{
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->UnregisterVariable("brightness");
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

            //Wenn sendende ID Variable mit Ident "Value" der KNX an/aus DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "on" entsprechend setzen und wenn vorhanden Hue-Status auch entsprechend setzen
	        if($SenderID == IPS_GetObjectIDByIdent("Value",$knxOnID) && $Message == 10603){
                $this->SetValue("on", $Data[0]);
            }
            //Wenn sendende ID Variable mit Ident "Value" der KNX Dim DPT und Message = 10603 (Variable aktualisiert) dann
            //    eigene Variable mit Ident "brightness" entsprechend setzen und wenn vorhanden HueBrightness auch entsprechend setzen
            if($SenderID == IPS_GetObjectIDByIdent("Value",$knxBrightnessID) && $Message == 10603){
                $this->SetValue("brightness",$Data[0]);
            }

			
		}

        public function RequestAction($Ident, $Value) {

            switch($Ident) {
                case "on":
                    $this->SetValue("on", $Value);
                    KNX_WriteDPT1($this->ReadPropertyInteger("KNXieaID"), $Value);
                    break;
                case "brightness":
                    $this->SetValue("brightness", $Value);
                    KNX_WriteDPT5($this->ReadPropertyInteger("KNXdimvalueID"), $Value);
                    break;
                default:
                    throw new Exception("Invalid Ident");
            }

        }


    }
?>