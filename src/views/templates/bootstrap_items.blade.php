@foreach($items as $item)
	<li @if(!empty($item['children'])) class="dropdown" @endif >
		<a href="{{$item['url']}}" @if(!empty($item['children'])) class="dropdown-toggle" data-toggle="dropdown" @endif>{{$item['name']}} @if(!empty($item['children'])) <span class="caret"></span> @endif </a>
		@if(!empty($item['children']))
			<ul class="dropdown-menu">
				@include('MenuView::templates.bootstrap_items', ['items'=>$item['children']])
			</ul>
		@endif
	</li>
@endforeach