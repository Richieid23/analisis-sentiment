<!DOCTYPE html>
<html>
<head>
	<title>Bobot Tweet</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>

	<div class="container">
		<center>
			<h4>Bobot Tweet</h4>
		</center>

		@if(session('status'))
            <div class="alert alert-success">
                {{session('status')}}
            </div>
        @endif

        <div class="row">
            <form method="post" action="{{ route('bobottweet.process') }}" enctype="multipart/form-data">
			{{ csrf_field() }}
                <button type="submit" class="btn btn-primary">Process TF</button>
            </form>
        </div> <br>

		<table class='table table-bordered table-striped table-hover'>
			<thead>
				<tr>
					<th>No</th>
					<th>Tweet</th>
                    <th>Bobot</th>
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($data as $s)
                @php
                    $tweet = App\Models\Preprocessing::findOrFail($s->tweet_id);
                @endphp
				<tr>
					<td>{{ $i++ }}</td>
                    <td>{{ $tweet->tweets }}</td>
                    <td>{{ $s->bobot }}</td>
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
