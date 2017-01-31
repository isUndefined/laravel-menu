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
	
		<div class="box box-success menus">
			<div class="box-body">
				<form id="form-menu-create" action="/backend/menu/category/store" method="POST">
					<input type="hidden" name="_token" value="{!! csrf_token() !!}" />
					<div class="row">
						<div class="col-sm-12">
							@if(session('msg'))
								<div class="alert alert-danger alert-dismissible">
									 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									{{ session('msg') }}
								</div>
							@endif
							<div class="form-group">
								<label>Name*</label>
								<input type="text" class="form-control" name="slug" placeholder="Menu name"/>
								<small>Example: mainMenu. Can contain only letter and numbers without spaces.</small>
							</div>
						</div>							
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="form-buttons loading-indicator-container">

								<!-- Save -->
								<button type="submit" class="btn btn-primary btn-flat save">
									Create    
								</button>
								&nbsp;&nbsp;or&nbsp;&nbsp;
								<!-- Delete -->
								<a href="{!! url('/backend/menu') !!}" class="btn btn-flat btn-default">Cancel</a>
							</div>
						</div>
					</div>
				</form>				
			</div>
		</div>

		
	</section>
@stop