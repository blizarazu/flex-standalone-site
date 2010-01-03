package commands.autoevaluation {
	import business.AutoEvaluationDelegate;

	import com.adobe.cairngorm.commands.ICommand;
	import com.adobe.cairngorm.control.CairngormEvent;

	import events.EvaluationEvent;

	import mx.controls.Alert;
	import mx.rpc.IResponder;
	import mx.rpc.events.FaultEvent;
	import mx.utils.ObjectUtil;

	public class EnableAutoevaluationResponseCommand implements ICommand, IResponder {

		public function execute(event:CairngormEvent):void {
			new AutoEvaluationDelegate(this).enableTranscriptionToResponse((event as EvaluationEvent).responseID, (event as EvaluationEvent).transcriptionSystem);
		}

		public function result(data:Object):void {

		}

		public function fault(info:Object):void {
			var faultEvent:FaultEvent = FaultEvent(info);
			Alert.show("Error: " + faultEvent.message);
			trace(ObjectUtil.toString(info));
		}
	}
}