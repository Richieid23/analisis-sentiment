<!DOCTYPE html>
<html>
<head>
	<title>Real-time Crawling Twitter</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

	<div class="container">
		<center>
			<h4>Real-time Crawling Twitter</h4>
		</center> <br><br>

		{{-- notifikasi form validasi --}}
		@if ($errors->has('file'))
		<span class="invalid-feedback" role="alert">
			<strong>{{ $errors->first('file') }}</strong>
		</span>
		@endif

		{{-- notifikasi sukses --}}
		@if ($sukses = Session::get('sukses'))
		<div class="alert alert-success alert-block">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>{{ $sukses }}</strong>
		</div>
		@endif

        <div class="row">
            <button type="button" class="btn btn-primary mr-5" data-toggle="modal" data-target="#importExcel">Real-time Crawling</button>
            <a href="{{ route('preprocessing') }}" class="btn btn-success">Preprocessing</a>
        </div> <br>

		<!-- Import Excel -->
		<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('crawling.process') }}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Crawling</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}

							<label>Query</label>
                            <input type="text" name="query" required="required">
							<br>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Crawl</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<table class='table table-striped table-hover'>
			<thead>
				<tr>
					<th>No</th>
					<th>Tweet</th>
                    {{-- <th>tanggal</th> --}}
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($data as $s)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{$s->tweets}}</td>
                    {{-- <td>{{ $s->created_at }}</td> --}}
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>


	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
