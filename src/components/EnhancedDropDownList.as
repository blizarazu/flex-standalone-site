package components
{
	import com.adobe.utils.ArrayUtil;
	
	import flash.events.Event;
	import flash.text.TextField;
	import flash.text.TextFormat;
	
	import mx.collections.ArrayCollection;
	import mx.collections.ICollectionView;
	import mx.collections.IList;
	import mx.collections.IViewCursor;
	import mx.collections.ListCollectionView;
	import mx.events.CollectionEvent;
	import mx.events.FlexEvent;
	import mx.resources.ResourceManager;
	import mx.utils.ObjectUtil;
	
	import spark.components.DropDownList;
	import spark.globalization.SortingCollator;

	public class EnhancedDropDownList extends DropDownList
	{
		private var _sorter:SortingCollator=new SortingCollator();
		private var _sortItems:Boolean;
		private var sortItemsChanged:Boolean;
		
		private var dataProviderLocaleChanged:Boolean;
		private var dataProviderOrderChanged:Boolean;

		private var _localeAwareDataProvider:IList;
		private var _localeAwarePrompt:String;

		private var _indexField:String="code";

		private var localeAwarePromptChanged:Boolean;
		private var localeAwareDataProviderChanged:Boolean;
		private var indexFieldChanged:Boolean;

		private var previousSelectedItem:Object;

		[Bindable("localeAwareDataProviderChanged")]
		[Bindable("localeAwarePromptChanged")]
		[Bindable("indexFieldChanged")]

		public function EnhancedDropDownList()
		{
			super();
			ResourceManager.getInstance().addEventListener(Event.CHANGE, localeChangeHandler, false, 0, true);
		}

		protected function localeChangeHandler(event:Event):void
		{
			if (localeAwareDataProvider)
			{
				updateLocalizedList();
			}
			if (localeAwarePrompt)
			{
				updateLocalizedPrompt();
			}
		}
		
		protected function findOldItemIndex(collection:ArrayCollection):int{
			var itemIndex:int=-1;
			if (previousSelectedItem && previousSelectedItem.hasOwnProperty(indexField))
			{
				var internalSortingValue:*=previousSelectedItem[indexField];
				var item:Object=findItemByField(collection, indexField, internalSortingValue);
				previousSelectedItem=null;
				if (item)
				{
					itemIndex=collection.getItemIndex(item);
				}
			}
			return itemIndex;
		}

		override public function set dataProvider(value:IList):void
		{
			if (_sortItems)
			{
				previousSelectedItem=selectedItem;
				var sortedDataProvider:ArrayCollection=sortList(value);
				var oldItemIndex:int=findOldItemIndex(sortedDataProvider);
				if(oldItemIndex > -1){
					selectedIndex=oldItemIndex;
				}
				
				//set dataProvider calls invalidateProperties() in a parent class
				//so our commitProperties flags should be set after this step is done
				super.dataProvider=sortedDataProvider;
				//dataProviderOrderChanged=true;
			}
			else
			{
				super.dataProvider=value;
			}
			invalidateProperties();
		}

		override protected function commitProperties():void
		{
			super.commitProperties();
			if (sortItemsChanged)
			{
				sortItemsChanged=false;
				updateDataProvider(dataProvider);
			}
			
			var widestItem:Object=getWidestItem();
			if (widestItem)
			{
				typicalItem=widestItem;
			}
		}

		private function updateDataProvider(value:IList):void
		{
			dataProvider=value;
		}

		private function updateLocalizedList():void
		{
			dataProvider=localizeList(_localeAwareDataProvider);
		}

		private function updateLocalizedPrompt():void
		{
			prompt=ResourceManager.getInstance().getString('myResources', _localeAwarePrompt);
		}

		protected function localizeList(value:IList):ArrayCollection
		{
			var localizedCollection:ArrayCollection=new ArrayCollection();
			if (value)
			{
				var collection:ICollectionView=new ListCollectionView(IList(value));
				var iterator:IViewCursor=collection.createCursor();
				while (!iterator.afterLast)
				{
					var item:Object=iterator.current;
					var itemCopy:Object=ObjectUtil.clone(item);
					if (item is String)
					{
						itemCopy=ResourceManager.getInstance().getString('myResources', (item as String));
					}
					else
					{
						if (item.hasOwnProperty(labelField))
						{
							itemCopy[labelField]=ResourceManager.getInstance().getString('myResources', item[labelField]);
						}
					}
					localizedCollection.addItem(itemCopy);

					iterator.moveNext();
				}
			}
			dataProviderLocaleChanged=true;
			return localizedCollection;
		}

		protected function sortList(value:IList):ArrayCollection
		{
			var sortedCollection:ArrayCollection;
			var input:Array,output:Array;
			var i:Object,copy:Object;
			if (value)
			{
				input=value.toArray();
				output=new Array();
				
				for each(i in input){
					copy = ObjectUtil.copy(i);
					output.push(copy);
				}
				output.sort(this.localizedSorting);
				sortedCollection=new ArrayCollection(output);
			}
			//Make sure set dataProvider's deep equal doesn't compare the same reference
			return ObjectUtil.copy(sortedCollection) as ArrayCollection;
		}
		
		/*
		protected function sortListIdxField(value:IList):ArrayCollection{
			var sortedCollection:ArrayCollection;
			var input:Array, output:Array;
			if(value){
				input=value.toArray();
				output=input.sort(this.localizedSorting,Array.RETURNINDEXEDARRAY);
				sortedCollection=new ArrayCollection(output);
			}
			return sortedCollection;
		}*/

		protected function findItemByField(list:IList, fieldName:String, fieldValue:*):Object
		{

			if (!list || !list.length || !fieldName)
				return null;

			var foundItem:Object=null;
			var collection:ICollectionView=new ListCollectionView(IList(list));
			var iterator:IViewCursor=collection.createCursor();
			while (!iterator.afterLast)
			{
				var item:Object=iterator.current;
				if (item && item.hasOwnProperty(fieldName) && item[fieldName] == fieldValue)
				{
					foundItem=item;
					break;
				}
				iterator.moveNext();
			}
			return foundItem;
		}

		protected function localizedSorting(item1:Object, item2:Object):int
		{
			var label1:String=getItemLabel(item1);
			var label2:String=getItemLabel(item2);
			_sorter.setStyle('locale', ResourceManager.getInstance().localeChain[0]);
			_sorter.ignoreCase=true;
			return _sorter.compare(label1, label2);
		}

		protected function getWidestItem():Object
		{
			var widestItem:Object=null;
			var twidth:uint=this.minWidth;
			var format:TextFormat=new TextFormat();
			format.font=this.getStyle("font-family");
			format.size=this.getStyle("font-size");
			var textField:TextField=new TextField;
			textField.setTextFormat(format);

			var o:Object;
			var text:String;
			if (this.dataProvider)
			{
				for (var i:uint=0; i < this.dataProvider.length; i++)
				{
					o=this.dataProvider.getItemAt(i);
					text=getItemLabel(o);
					textField.text=text ? text : "";
					if (textField.textWidth > twidth)
					{
						twidth=textField.textWidth;
						widestItem=o;
					}
				}
			}
			return widestItem;
		}

		protected function getItemLabel(item:Object):String
		{
			if (!item)
				return "";

			var label:String;
			if (this.labelFunction != null)
			{
				label=this.labelFunction(item);
			}
			else
			{
				label=item.hasOwnProperty(this.labelField) ? item[this.labelField] : item as String;
			}
			return label;
		}


		public function get localeAwareDataProvider():IList
		{
			return _localeAwareDataProvider;
		}

		public function set localeAwareDataProvider(value:IList):void
		{
			if (localeAwareDataProvider === value)
				return;

			_localeAwareDataProvider=value;
			localeAwareDataProviderChanged=true;

			dataProvider=localizeList(_localeAwareDataProvider);

			dispatchEvent(new Event("localeAwareDataProviderChanged"));
		}

		public function set localeAwarePrompt(value:String):void
		{
			if (localeAwarePrompt === value)
				return;
			_localeAwarePrompt=value;
			localeAwarePromptChanged=true;

			prompt=ResourceManager.getInstance().getString('myResources', _localeAwarePrompt);

			dispatchEvent(new Event("localeAwarePromptChanged"));
		}

		public function get localeAwarePrompt():String
		{
			return _localeAwarePrompt;
		}

		public function get indexField():String
		{
			return _indexField;
		}

		public function set indexField(value:String):void
		{
			if (value == _indexField)
				return;

			_indexField=value;
			indexFieldChanged=true;
			invalidateProperties();
		}

		public function set sortItems(value:Boolean):void
		{
			if (value == _sortItems)
				return;

			_sortItems=value;
			sortItemsChanged=true;
			invalidateProperties();
		}

		public function get sortItems():Boolean
		{
			return _sortItems;
		}
	}
}