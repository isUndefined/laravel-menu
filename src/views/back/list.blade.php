@extends('backoffice.app')
@section('content')
	<section class="content-header">
		<h1>
			Menu
			<small>it all starts here</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Examples</a></li>
			<li class="active">Blank page</li>
		</ol>
    </section>
	<section class="content">
		<!-- Default box -->
		<div class="box">
			<div class="box-header with-border">
			  <h3 class="box-title">Menus</h3>

			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
				  <i class="fa fa-minus"></i>
				</button>				
			  </div>
			</div>
			<div class="box-body table-responsive no-padding">
                <div class="col-sm-12">					
					@if(session('msg'))
						<br>
						<div class="alert alert-success alert-dismissible">
							 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							{{ session('msg') }}
						</div>
					@endif
					<br>
					<div class="form-group">
						<a href="{!! url('backend/menu/create') !!}" class="btn btn-flat btn-primary"><i class="fa fa-plus"></i> New menu</a> &nbsp;&nbsp;
						<a class="btn btn-default btn-flat disabled delete-selected" data-href="/backend/menus/delete/" data-checkbox-name="menus" onclick="if(!confirm('Are you sure? All menus will be deleted.')) return false;"><i class="fa fa-trash-o"></i> Delete selected</a>
					</div>
				</div>
				<table class="table table-hover">
                    <tr>
                      <th class="text-center" width="52">
						<label>
						  <input type="checkbox" class="minimal select-all">
						</label>
					  </th>
                      <th>NAME</th>
                      <th>MENU ITEMS</th>
						@forelse($menus as $menu)
							<tr>
								<td class="text-center">
									<label>
									  <input type="checkbox" class="minimal" name="menus[]" value="{{ $menu->id }}">
									</label>
								</td>
								<td><a href="{!! url('/backend/menu') !!}/{{ $menu->id }}">{{ $menu->slug }}</a></td>
								<td>{{ $menu->items()->count() }}</td>
							</tr>
						@empty
							<tr>
								<td colspan="3" class="text-center">No data.</td>
							</tr>
						@endforelse
                    </tr>
					</table>
			</div><!-- /.box-body -->
			<!-- /.box-body -->
			<div class="box-footer">
			  Footer
			</div>
			<!-- /.box-footer-->
		</div>
		<!-- /.box -->
	</section>
@stop