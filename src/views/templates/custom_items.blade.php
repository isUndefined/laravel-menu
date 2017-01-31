@foreach($items as $item)
	<li @if($item['parent_id']==0) class="col-md-2" @endif>
		<a href="{{$item['url']}}" @if(!empty($item['children'])) class="dropdown-toggle" data-toggle="dropdown" @endif>
			<i class="red fa {{$item['icon']}}"></i> 
			@if(checkL18n())
				@if(!empty($item['translate'][app()->getLocale()]))
					{{ $item['translate'][app()->getLocale()]['name'] }}
				@else 
					{{$item['name']}} 
				@endif
			@else
				{{$item['name']}} 
			@endif
			
			
			@if(!empty($item['children'])) <i class="fa fa-angle-down"></i> @endif 
			@if($item['parent_id']==0)<span>-</span>@endif
		</a>
		@if(!empty($item['children']))
			<ul class="dropdown-menu">
				@include('MenuView::templates.custom_items', ['items'=>$item['children']])
			</ul>
		@endif
	</li>
@endforeach