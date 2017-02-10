@extends('backoffice.app')

@section('content')
	<section class="content-header">
		<h1>
			Menus <small>Editor</small>
		</h1>
		<ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Menus</a></li>
            <li class="active"> Editor </li>       
		</ol>
    </section>
	<section class="content ">
			<!-- Your Page Content Here -->
					
		<div class="box box-success menus">
			<!--<div class="box-header"></div>-->
			<div class="box-body">
				<div class="row">
					<div class="col-md-4 col-lg-4">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab-custom-link" data-toggle="tab" aria-expanded="true">Custom Links</a></li>
								<li class=""><a href="#tab-modules" data-toggle="tab" aria-expanded="false">Modules</a></li>								
							</ul>
							<div class="tab-content">
								
								<div class="tab-pane active" id="tab-custom-link">			
									@if(session('msg'))
										<div class="alert alert-success alert-dismissible">
											 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											{{ session('msg') }}
										</div>
									@endif
									<form method="POST" action="/backend/menu/create" accept-charset="UTF-8" id="menu-custom-form" novalidate="novalidate">
										{{ csrf_field() }}
										<input type="hidden" name="category" value="{{$menus->id}}" />
										<div class="form-group">
											<label for="url">URL</label>
											<input class="form-control" placeholder="URL" name="url" type="text" value="http://">
										</div>
										<div class="form-group">
											<label for="name">Label</label>
											@if (checkL18n())
												{!! L18n::GenerateField(['field'=>['type'=>'text','name'=>'name']]) !!}
											@else
												<input class="form-control" placeholder="Label" name="name" type="text" value="">
											@endif
										</div>
										<div class="form-group">
											<label for="icon">Icon</label>
											<div class="input-group iconpicker-container">
												<input class="form-control" placeholder="FontAwesome Icon" name="icon" type="text" value="fa-cube">
												<span class="input-group-btn">
													<button class="btn btn-default iconpicker"></button>
												</span> 
											</div>
										</div>	
										<input type="submit" class="btn btn-primary mr10" value="Add to menu">
									</form>
								</div>
								<div class="tab-pane" id="tab-modules">
									<em>Empty list.</em>
								</div>
							</div><!-- /.tab-content -->
						</div><!-- nav-tabs-custom -->
					</div>
					<div class="col-md-8 col-lg-8">
						<div class="dd" id="menu-nestable">
							@if($menus->items()->count())
							<ol class="dd-list">
								{!! $renderMenu !!}
							</ol>
							@endif

						</div>
					</div>
				</div>
			</div>
		</div>

		
		</section>
@stop

@section('admin_head_css')
	<!-- Bootstrap-Iconpicker -->
	<link rel="stylesheet" href="/js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
@stop
@section('admin_footer_js')
<script src="/js/plugins/nestable/jquery.nestable.js"></script>
<script src="/js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
<script src="/js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.js"></script>
<script>
	// Default options
	function generateIconpicker(){
		$('.iconpicker').iconpicker({
			rows: 5,
			cols: 6,
			icon: 'fa-cube',
			iconset: 'fontawesome'
		}).on('change', function(e){	
			var ic = '';
			if(e.icon!="empty"){
				ic = e.icon;
			}
			$(e.currentTarget).closest('.iconpicker-container').find('.form-control').val(ic);
		});
	}
		
		
	$(document).ready(function(){
		generateIconpicker();
		
		$('#menu-nestable').nestable({
			group: 1,
			dropCallback: function(e){
				formData = { source: e.sourceId, menus: e.destParent.data('menu'), _token: SECURITY_TOKEN, hierarhy: $('#menu-nestable').nestable('serialize')  };
				$.ajax({
					type: 'POST',
					url: '/backend/menu/ajax-update-position',
					data: formData,
					cache: false,
					success: function(res){		
						$.notify('Menu updated.', 'success');	
					}
				});
			}
		});
		
		$('#menu-nestable .btn-edit').click(function(e){
			e.preventDefault();
			var data = JSON.parse($(this).attr('data-info'));
					
			$.ajax({
				type: 'POST',
				url: '/backend/menu/ajax-show-edit-menu',
				data: { _token: SECURITY_TOKEN, menus:data.id  },
				cache: false,
				success: function(res){		
					
					$('.menus').append(res.html);
					generateIconpicker();
					
					if(data.icon){
						$('#EditModal .iconpicker').iconpicker('setIcon',data.icon);
						$('#EditModal input[name="icon"]').val(data.icon);		
					}
					$('#menu-edit-form').append('<input type="hidden" name="menu_data" value=\''+JSON.stringify(data)+'\' />');
					
					
					/* $('#EditModal input[name="url"]').val('http://');
					$('#EditModal input[name="name"]').val('');
					$('#EditModal input[name="icon"]').val('');
					$('#EditModal .iconpicker').iconpicker('setIcon','empty');
					$('#menu-edit-form input[name="menu_data"]').remove();
					
					
					
					if(data.url){
						$('#EditModal input[name="url"]').val(data.url);
					}
					if(data.name){
						$('#EditModal input[name="name"]').val(data.name);
					} */
					
					$('#EditModal').modal('show');
				}
			});
		
			return false;
			
		});
		
	
		
		
		$(document).on("submit",'#menu-edit-form',function(e){
			e.preventDefault();
			
			var formData = $(this).serializeArray(),
				_this = this,
				info = $('#menu-edit-form input[name="menu_data"]').val();
				
			$.ajax({
				type: 'POST',
				url: '/backend/menu/ajax-update-menu',
				data: formData,
				cache: false,
				success: function(res){		
					
					if(res.status=='error'){
						for(var i in res.msg){
							$.notify(res.msg[i], 'error');	
						}
					} else {
						$.notify(res.msg, 'success');
						
						//
						var objMenus = $('#menu-nestable .dd-item[data-menu="'+JSON.parse(info).menus_id+'"]');
						
						var newName = $('input[name="name"]',_this).val();
						var newIcon = $('input[name="icon"]',_this).val();
						var newUrl = $('input[name="url"]',_this).val();
						
						// * For L18Translate
						if($('.field-multilingual',_this).length){
							var defaultLang = $('input[name="default-lang"]').val();
							newName = $('.field-multilingual input[name="L18nTranslate['+defaultLang+'][name]"]',_this).val();
						}
						
						$('>.dd3-content h6',objMenus).text('').text(newName);
						$('>.dd3-content i',objMenus).eq(0).attr('class','fa '+newIcon);
						
						var newInfo = JSON.parse(info);
						newInfo.name = newName;
						newInfo.icon = newIcon;
						newInfo.url = newUrl;
						$('>.dd3-content .btn-edit',objMenus).attr('data-info',JSON.stringify(newInfo));
						
						$('#EditModal').modal('hide');	
					
					}
				}
			});
			
			return false;
		});
		
		
		$(document).on('hidden.bs.modal','#EditModal', function (e) {
			$('#EditModal').remove();
		});

	});
	
	
</script>
@stop