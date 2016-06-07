@extends('admin.master')

@section('title', 'Admin Dashboard')

@section('head')
	<script language="javascript" type="text/javascript" src="{{ URL::asset("js/flot/jquery.flot.js") }}"></script>
	<script language="javascript" type="text/javascript" src="{{ URL::asset("js/flot/jquery.flot.pie.js") }}"></script>
@stop

@section('content')
	<div class="row placeholders">
		<div class="col-xs-6 col-sm-3 placeholder">
			<div class="placeholder-item" id="placeholder1"></div>
			<a class="btn btn-default" href="{{ url("admin/users") }}">Admin Users</a><br />
			<span class="text-muted">Create, edit, deactivate,<br />and delete users</span>
		</div>
		<div class="col-xs-6 col-sm-3 placeholder">
			<div class="placeholder-item" id="placeholder2"></div>
			<a class="btn btn-default" href="{{ url("admin/forum/categories") }}">Admin Forum</a><br />
			<span class="text-muted">Moderate forum categories<br />and permissions</span>
		</div>
		<div class="col-xs-6 col-sm-3 placeholder">
			<div class="placeholder-item" id="placeholder3"></div>
			<a class="btn btn-default" href="#">Admin Label</a><br />
			<span class="text-muted">Something else</span>
		</div>
		<div class="col-xs-6 col-sm-3 placeholder">
			<div class="placeholder-item" id="placeholder4"></div>
			<a class="btn btn-default" href="#">Admin Label</a><br />
			<span class="text-muted">Something else</span>
		</div>
	</div>
@stop

@section('after_content')
	<script type="text/javascript">
		var data = [],
		series = Math.floor(Math.random() * 6) + 3;

		for (var i = 0; i < series; i++) {
			data[i] = {
				label: "Series" + (i + 1),
				data: Math.floor(Math.random() * 100) + 1
			}
		}

		var user_data = <? echo json_encode( App\Models\User::stats() ) ?>;

		$.plot('#placeholder1', user_data, {
			series: {
				pie: {
					innerRadius: 0.5,
                    radius: 1,
					show: true,
					label: {
						show: true,
						radius: 3/4,
						background: {
							opacity: 0.5,
							color: '#000'
						}
					}
				}
			},
			legend: {
				show: false
			}
		});

		$.plot('#placeholder2', data, {
            series: {
				pie: {
					innerRadius: 0.5,
                    radius: 1,
					show: true,
					label: {
						show: true,
						radius: 3/4,
						background: {
							opacity: 0.5,
							color: '#000'
						}
					}
				}
			},
			legend: {
				show: false
			}
		});

		$.plot('#placeholder3', data, {
            series: {
				pie: {
					innerRadius: 0.5,
                    radius: 1,
					show: true,
					label: {
						show: true,
						radius: 3/4,
						background: {
							opacity: 0.5,
							color: '#000'
						}
					}
				}
			},
			legend: {
				show: false
			}
		});

		$.plot('#placeholder4', data, {
            series: {
				pie: {
					innerRadius: 0.5,
                    radius: 1,
					show: true,
					label: {
						show: true,
						radius: 3/4,
						background: {
							opacity: 0.5,
							color: '#000'
						}
					}
				}
			},
			legend: {
				show: false
			}
		});
	</script>
@stop
