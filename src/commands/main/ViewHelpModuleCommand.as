package commands.main
{
	import com.adobe.cairngorm.commands.ICommand;
	import com.adobe.cairngorm.control.CairngormEvent;
	
	import control.BabeliaBrowserManager;
	
	import events.CloseConnectionEvent;
	import events.ViewChangeEvent;
	
	import model.DataModel;
	
	import modules.main.HelpFAQMain;
	
	import spark.components.Group;
	import spark.components.SkinnableContainer;
	
	public class ViewHelpModuleCommand implements ICommand
	{

		
		public function execute(event:CairngormEvent):void
		{
			var index:uint = ViewChangeEvent.VIEWSTACK_HELP_MODULE_INDEX;
			DataModel.getInstance().currentContentViewStackIndex = index;
			
			
			BabeliaBrowserManager.getInstance().updateURL(
				BabeliaBrowserManager.index2fragment(index));
		}
	
	}
}