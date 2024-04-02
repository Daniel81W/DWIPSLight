<?php /** @noinspection PhpUnused */
/** @noinspection PhpExpressionResultUnusedInspection */
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

            $knxeaID = $this->ReadPropertyInteger("KNXieaID");
            $knxDimID = $this->ReadPropertyInteger("KNXdimvalueID");

            if($knxeaID > 1) {
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableBoolean("on", "Status", "~Switch", 1);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("on");

                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage(@IPS_GetObjectIDByIdent("Value", $knxeaID), 10603);
            }

            if($knxDimID > 1){
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterVariableInteger("brightness", "Helligkeit", "~Intensity.100", 2);
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->EnableAction("brightness");
                /** @noinspection PhpExpressionResultUnusedInspection */
                $this->RegisterMessage(@IPS_GetObjectIDByIdent("Value", $knxDimID), 10603);

            }elseif (@$this->GetIDForIdent("brightness")>1){
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