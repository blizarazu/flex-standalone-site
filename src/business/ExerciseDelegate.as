package business
{
	import com.adobe.cairngorm.business.ServiceLocator;
	
	import mx.rpc.AsyncToken;
	import mx.rpc.IResponder;
	import mx.rpc.remoting.RemoteObject;
	
	import vo.ExerciseReportVO;
	import vo.ExerciseScoreVO;
	import vo.ExerciseVO;


	public class ExerciseDelegate
	{

		public var responder:IResponder;

		public function ExerciseDelegate(responder:IResponder)
		{
			this.responder=responder;
		}
		
		public function addUnprocessedExercise(exercise:ExerciseVO):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.addUnprocessedExercise(exercise);
			pendingCall.addResponder(responder);
		}
		
		public function addWebcamExercise(exercise:ExerciseVO):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.addWebcamExercise(exercise);
			pendingCall.addResponder(responder);
		}
		
		public function getExercises():void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.getExercises();
			pendingCall.addResponder(responder);
		}
		
		public function getRecordableExercises(params:Object):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.getRecordableExercises(params);
			pendingCall.addResponder(responder);
		}

		public function getExerciseLocales(mediaid:int):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.getExerciseLocales(mediaid);
			pendingCall.addResponder(responder);
		}
		
		/*
		public function getExercisesWithoutSubtitles():void{
			var service:RemoteObject = ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken = service.getExercisesWithoutSubtitles();
			pendingCall.addResponder(responder);
		}
		*/
		
		public function getExercisesWithoutSubtitles():void{
			var service:RemoteObject = ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken = service.getExercisesUnfinishedSubtitling();
			pendingCall.addResponder(responder);
		}
		
		public function getExercisesToReviewSubtitles():void{
			var service:RemoteObject = ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken = service.getExercisesToReviewSubtitles();
			pendingCall.addResponder(responder);
		}
		
		public function addInappropriateExerciseReport(report:ExerciseReportVO):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.addInappropriateExerciseReport(report);
			pendingCall.addResponder(responder);
		}
		
		public function userReportedExercise(report:ExerciseReportVO):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.userReportedExercise(report);
			pendingCall.addResponder(responder);
		}
		
		public function addExerciseScore(score:ExerciseScoreVO):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.addExerciseScore(score);
			pendingCall.addResponder(responder);
		}
		
		public function userRatedExercise(score:ExerciseScoreVO):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.userRatedExercise(score);
			pendingCall.addResponder(responder);
		}
		
		public function watchExercise(exercisehash:String):void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.watchExercise(exercisehash);
			pendingCall.addResponder(responder);
		}
		
		public function requestRecordingSlot():void{
			var service:RemoteObject=ServiceLocator.getInstance().getRemoteObject("exerciseRO");
			var pendingCall:AsyncToken=service.requestRecordingSlot();
			pendingCall.addResponder(responder);
		}
		
	}
}