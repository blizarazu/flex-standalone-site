package control {
	import com.adobe.cairngorm.control.FrontController;
	
	import commands.autoevaluation.*;
	import commands.main.*;
	import commands.userManagement.*;
	import commands.videoSlice.*;
	
	import events.*;
	
	import modules.account.command.*;
	import modules.assessment.command.*;
	import modules.assessment.event.*;
	import modules.course.command.*;
	import modules.course.event.*;
	import modules.exercise.command.*;
	import modules.exercise.event.*;
	import modules.home.command.*;
	import modules.home.event.*;
	import modules.activity.command.GetUserActivity;
	import modules.activity.event.UserActivityEvent;
	import modules.subtitle.command.*;
	import modules.subtitle.event.*;

	public class Controller extends FrontController {
		//All the application's actions are managed from this controller
		public function Controller() {
			super();
			
			//Connection management commands
			addCommand(FullStreamingEvent.SETUP_CONNECTION, SetupConnectionCommand);
			addCommand(FullStreamingEvent.START_CONNECTION, StartConnectionCommand);
			addCommand(FullStreamingEvent.CLOSE_CONNECTION, CloseConnectionCommand);
			
			addCommand(CourseEvent.GET_COURSES, GetCourses);
			addCommand(CourseEvent.VIEW_COURSE, ViewCourse);
			
			//Credit management commands
			addCommand(CreditEvent.GET_ALL_TIME_CREDIT_HISTORY, GetAllTimeCreditHistoryCommand);
			addCommand(CreditEvent.GET_CURRENT_DAY_CREDIT_HISTORY, GetCurrentDayCreditHistoryCommand);
			addCommand(CreditEvent.GET_LAST_WEEK_CREDIT_HISTORY, GetLastWeekCreditHistoryCommand);
			addCommand(CreditEvent.GET_LAST_MONTH_CREDIT_HISTORY, GetLastMonthCreditHistoryCommand);
			
			//Homepage management commands
			addCommand(MessageOfTheDayEvent.UNSIGNED_MESSAGES_OF_THE_DAY, UnsignedMessageOfTheDayCommand);
			addCommand(MessageOfTheDayEvent.SIGNED_OF_THE_DAY, SignedMessageOfTheDayCommand);
			addCommand(HomepageEvent.LATEST_RECEIVED_ASSESSMENTS, UsersLatestReceivedAssessmentsCommand);
			addCommand(HomepageEvent.LATEST_DONE_ASSESSMENTS, UsersLatestGivenAssessmentsCommand);
			addCommand(HomepageEvent.LATEST_USER_UPLOADED_VIDEOS, UsersLatestUploadedVideosCommand);
			addCommand(HomepageEvent.BEST_RATED_VIDEOS_SIGNED_IN, SignedBestRatedVideosCommand);
			addCommand(HomepageEvent.BEST_RATED_VIDEOS_UNSIGNED, UnsignedBestRatedVideosCommand);
			addCommand(HomepageEvent.LATEST_UPLOADED_VIDEOS, LatestUploadedVideosCommand);	
			
			//Video history management commands
			addCommand(UserVideoHistoryEvent.STAT_EXERCISE_WATCH, VideoHistoryWatchCommand);
			addCommand(UserVideoHistoryEvent.STAT_ATTEMPT_RESPONSE, VideoHistoryAttemptCommand);
			addCommand(UserVideoHistoryEvent.STAT_SAVE_RESPONSE, VideoHistorySaveCommand);

			//Preference management commands
			addCommand(PreferenceEvent.GET_APP_PREFERENCES, GetAppPreferencesCommand);

			//User management commands
			addCommand(UserEvent.GET_TOP_TEN_CREDITED, GetTopTenCreditedCommand);
			addCommand(UserEvent.KEEP_SESSION_ALIVE, KeepSessionAliveCommand);
			addCommand(UserEvent.MODIFY_PREFERRED_LANGUAGES, ModifyUserLanguagesCommand);
			addCommand(UserEvent.MODIFY_PERSONAL_DATA, ModifyPersonalDataCommand);
			addCommand(UserActivityEvent.GET_USER_ACTIVITY, GetUserActivity);

			//Login management commands
			addCommand(LoginEvent.PROCESS_LOGIN, ProcessLoginCommand);
			addCommand(LoginEvent.SIGN_OUT, SignOutCommand);
			addCommand(LoginEvent.RESTORE_PASS, RestorePassCommand);
			addCommand(LoginEvent.RESEND_ACTIVATION_EMAIL, ResendActivationEmailCommand);
			addCommand(ModifyUserEvent.CHANGE_PASS, ChangePassCommand);
			
			// User Registration management
			addCommand(RegisterUserEvent.REGISTER_USER, RegisterUserCommand);
			addCommand(RegisterUserEvent.ACTIVATE_USER, ActivateUserCommand);
			
			//VideoSlice management commands
			addCommand(VideoSliceEvent.SEARCH_URL, SearchUrlCommand);
			addCommand(VideoSliceEvent.SEARCH_USER, SearchUserCommand);
			addCommand(VideoSliceEvent.CREATE_SLICE, CreateSliceCommand);

			//Exercise management commands
			addCommand(ExerciseEvent.GET_EXERCISES, GetExercisesCommand);
			addCommand(ExerciseEvent.GET_RECORDABLE_EXERCISES, GetRecordableExercisesCommand);
			addCommand(ExerciseEvent.GET_EXERCISE_LOCALES, GetExerciseLocalesCommand);
			addCommand(ExerciseEvent.WATCH_EXERCISE, WatchExerciseCommand);
			addCommand(ExerciseEvent.EXERCISE_SELECTED, ExerciseSelectedCommand);
			addCommand(ExerciseEvent.RATE_EXERCISE, RateExerciseCommand);
			addCommand(ExerciseEvent.REPORT_EXERCISE, ReportInappropriateExerciseCommand);
			//addCommand(ExerciseEvent.USER_RATED_EXERCISE, UserRatedExerciseCommand);
			addCommand(ExerciseEvent.USER_REPORTED_EXERCISE, UserReportedExerciseCommand);
			addCommand(ExerciseEvent.REQUEST_RECORDING_SLOT, RequestRecordingSlot);
			
			//Evaluation management commands
			addCommand(EvaluationEvent.GET_RESPONSES_WAITING_ASSESSMENT, GetResponsesWaitingAssessmentCommand);
			addCommand(EvaluationEvent.GET_RESPONSES_ASSESSED_TO_CURRENT_USER, GetResponsesAssessedToCurrentUserCommand);
			addCommand(EvaluationEvent.GET_RESPONSES_ASSESSED_BY_CURRENT_USER, GetResponsesAssessedByCurrentUserCommand);
			addCommand(EvaluationEvent.ADD_ASSESSMENT, AddAssessmentCommand);
			addCommand(EvaluationEvent.ADD_VIDEO_ASSESSMENT, AddVideoAssessmentCommand);
			addCommand(EvaluationEvent.DETAILS_OF_RESPONSE_ASSESSED_TO_USER, DetailsOfAssessedResponseCommand);
			addCommand(EvaluationEvent.DETAILS_OF_RESPONSE_ASSESSED_BY_USER, DetailsOfAssessedResponseCommand);
			addCommand(EvaluationEvent.GET_EVALUATION_CHART_DATA, GetEvaluationChartDataCommand);
			addCommand(EvaluationEvent.GET_RESPONSE_DATA, GetResponseData);
			
			//Autoevaluation management commands
			addCommand(EvaluationEvent.AUTOMATIC_EVAL_RESULTS, AutoEvaluateCommand);
			addCommand(EvaluationEvent.ENABLE_TRANSCRIPTION_TO_EXERCISE, EnableAutoevaluationExerciseCommand);
			addCommand(EvaluationEvent.ENABLE_TRANSCRIPTION_TO_RESPONSE, EnableAutoevaluationResponseCommand);
			addCommand(EvaluationEvent.CHECK_AUTOEVALUATION_SUPPORT_EXERCISE, CheckAutoevaluationSupportExerciseCommand);
			addCommand(EvaluationEvent.CHECK_AUTOEVALUATION_SUPPORT_RESPONSE, CheckAutoevaluationSupportResponseCommand);
			
			//Response management commands
			addCommand(ResponseEvent.SAVE_RESPONSE, SaveResponseCommand);
			addCommand(ResponseEvent.MAKE_RESPONSE_PUBLIC, MakeResponsePublicCommand);
			addCommand(ResponseEvent.ADD_DUMMY_VIDEO, AddDummyVideoCommand);

			//Subtitle management commands
			addCommand(SubtitleEvent.SAVE_SUBAND_SUBLINES, SaveSubtitlesCommand);
			addCommand(SubtitleEvent.GET_EXERCISE_SUBLINES, GetExerciseSubtitleLinesCommand);
			addCommand(SubtitleListEvent.GET_EXERCISES_WITHOUT_SUBTITLES, GetExercisesWithoutSubtitlesCommand);
			addCommand(SubtitleListEvent.GET_EXERCISES_WITH_SUBTITLES_TO_REVIEW, GetExercisesReviewSubtitlesCommand);
			addCommand(SubtitleEvent.GET_MEDIA_SUBTITLES, GetMediaSubtitlesCommand);
			
		}

	}
}