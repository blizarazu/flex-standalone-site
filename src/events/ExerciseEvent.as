package events
{
	import com.adobe.cairngorm.control.CairngormEvent;
	
	import vo.ExerciseVO;

	public class ExerciseEvent extends CairngormEvent
	{

		public static const ADD_EXERCISE:String="addExercise";
		public static const GET_EXERCISES:String="getExercises";
		public static const WATCH_EXERCISE:String="watchExercise";
		public static const MAKE_PUBLIC:String="makeExercisePublic";

		public var exercise:ExerciseVO;
		public var response:Number;

		public function ExerciseEvent(type:String, exercise:ExerciseVO = null)
		{
			super(type);
			this.exercise=exercise;
		}

	}
}