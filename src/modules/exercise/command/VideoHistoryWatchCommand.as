package modules.exercise.command
{
	import business.VideoHistoryDelegate;
	
	import com.adobe.cairngorm.commands.ICommand;
	import com.adobe.cairngorm.control.CairngormEvent;
	
	import events.UserVideoHistoryEvent;
	
	import mx.resources.ResourceManager;
	import mx.rpc.IResponder;
	import mx.utils.ObjectUtil;
	
	import view.common.CustomAlert;
	
	public class VideoHistoryWatchCommand implements ICommand, IResponder
	{
		public function execute(event:CairngormEvent):void
		{
			new VideoHistoryDelegate(this).exerciseWatched((event as UserVideoHistoryEvent).params);
		}
		
		public function result(data:Object):void
		{
			//Do nothing, for now
		}
		
		public function fault(info:Object):void
		{
			CustomAlert.error(ResourceManager.getInstance().getString('myResources','ERROR_WHILE_ADDING_VIDEOHISTORY_ITEM'));
			trace(ObjectUtil.toString(info));
		}
	}
}