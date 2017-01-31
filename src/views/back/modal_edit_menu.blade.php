<div class="modal fade" id="EditModal" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span><i class="fa fa-close"></i></span></button>
			<h4 class="modal-title" id="myModalLabel">Edit Menu Item</h4>
		</div>
		<form method="POST" action="/" accept-charset="UTF-8" id="menu-edit-form" novalidate="novalidate">
			<input type="hidden" name="category" value="{{$menus->id}}" />
			{{ csrf_field() }}
			@if (checkL18n())
			<input type="hidden" name="default-lang" value="{{config('l18n.default')}}" />
			@endif
			<div class="modal-body">
				<div class="box-body">
					<div class="form-group">
						<label for="url" style="font-weight:normal;">URL</label>
						<input class="form-control" placeholder="URL" name="url" type="text" value="{{$menus_item->url}}">
					</div>
					<div class="form-group">
						<label for="name" style="font-weight:normal;">Label</label>
						@if (checkL18n())
							{!! L18n::GenerateField(['field'=>['type'=>'text','name'=>'name'],'obj'=>$menus,'module'=>'menusLocale']) !!}
						@else
							<input class="form-control" placeholder="Label" name="name" type="text" value="{{$menus->name}}">					
						@endif
					</div>
					<div class="form-group">
						<label for="icon" style="font-weight:normal;">Icon</label>
						<div class="input-group iconpicker-container">
							<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="{{$menus_item->icon}}">
							<span class="input-group-btn">
								<button class="btn btn-default iconpicker"></button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<input class="btn btn-success" type="submit" value="Submit">
			</div>
		</form>
	</div>
</div>
</div>
