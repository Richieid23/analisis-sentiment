@extends('layout')

@section('title', 'Analisis Sentiment Cyerbullying')

@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-box2 icon-gradient bg-happy-itmeo"></i>
            </div>
            <div>
                Analisi Sentiment Cyberbullying
                <div class="page-title-subheading">
                    Metode Support Vector Machine
                </div>
            </div>
        </div>


        <div class="page-title-actions">
            <button type="button" class="btn btn-primary mr-5" data-toggle="modal" data-target="#importExcel">IMPORT EXCEL</button>
        </div>


    </div>
</div>

@if (session('status'))
<div class="session-status" data-status="{{ session('status') }}"> </div>
@endif

<ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
    <li class="nav-item">
        <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#tab-content-1">
            <span>Dataset</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-2">
            <span>Preprocessing</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-3">
            <span>Pembobotan</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-4">
            <span>Data Latih</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-5">
            <span>Klasifikasi</span>
        </a>
    </li>
    {{-- <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-5" data-toggle="tab" href="#tab-content-6">
            <span>Diterima</span>
        </a>
    </li>
    <li class="nav-item">
        <a role="tab" class="nav-link" id="tab-6" data-toggle="tab" href="#tab-content-7">
            <span>Permintaan Gagal</span>
        </a>
    </li> --}}
</ul>

<div class="tab-content">
    <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
        @if(session('status'))
            <div class="alert alert-success">
                {{session('status')}}
            </div>
        @endif

         <div class="row">
            <form method="post" action="{{ route('preprocessing.process') }}" enctype="multipart/form-data">
			{{ csrf_field() }}
                <button type="submit" class="btn btn-primary mr-5">Process</button>
            </form>
            <a href="{{ route('pembobotan') }}" class="btn btn-success">Pembobotan</a>
        </div> <br>

		<table class='table table-bordered table-striped table-hover'>
			<thead>
				<tr>
					<th>No</th>
					<th>Tweet</th>
                    <th>Results</th>
				</tr>
			</thead>
			<tbody>
				@php $i=1 @endphp
				@foreach($data as $s)
				<tr>
					<td>{{ $i++ }}</td>
					<td>{{$s->tweets}}</td>
                    <td>{{$s->results}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">

    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">

    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">

    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-5" role="tabpanel">

    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-6" role="tabpanel">

    </div>
    <div class="tab-pane tabs-animation fade" id="tab-content-7" role="tabpanel">

    </div>
</div>
@endsection



@section('modal')
    <!-- Modal Detail -->
    <div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<form method="post" action="{{ route('import') }}" enctype="multipart/form-data">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
						</div>
						<div class="modal-body">

							{{ csrf_field() }}

							<label>Pilih file excel</label>
							<div class="form-group">
								<input type="file" name="file" required="required">
							</div>

						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Import</button>
						</div>
					</div>
				</form>
			</div>
		</div>
@endsection


@section('script')
<script>
    const sessionStatus = $('.session-status').data('status');

    if (sessionStatus){
        Swal.fire(
            'Berhasil!',
            sessionStatus,
            'success'
        )
    }

    $(document).ready(function(){
        $(document).on('click', '#set-detail', function(e){
            e.preventDefault();
            let content = '';

            var counter = $(this).data('counter');
            var nama = $(this).data('nama');
            var warna = $(this).data('warna');
            var size_jumlah = $(this).data('size_jumlah');
            var status = $(this).data('status');
            var level = $(this).data('level');
            var keterangan = $(this).data('keterangan');

            const href = $(this).data('href');
            const href_tidakvalid = $(this).data('href_tidakvalid');

            $('#modal-counter').text(counter);
            $('#modal-nama').text(nama);
            $('#modal-warna').text(warna);
            $('#modal-size_jumlah').html(size_jumlah);
            $('#modal-status').text(status);
            $('#modal-keterangan').text(keterangan);

            if (status == "dicek" && level == "marketing") {
                $("#judulModal").html("Cek Barang");
                content += '<a href="'+href_tidakvalid+'" class="btn btn-danger" id="btn-tidakvalid">Tidak Valid</a> <a href="'+href+'" class="btn btn-success" id="btn-validasi">Validasi</a>';
            } else if(status == "permintaan" && level == "gudang"){
                $("#judulModal").html("Permintaan Barang");
                content += '<a href="'+href_tidakvalid+'" class="btn btn-danger" id="btn-tidakada">Barang Kosong</a> <a href="'+href+'" class="btn btn-success" id="btn-validasi">Terima Permintaan</a>';
            } else if(status == "disiapkan" && level == "gudang"){
                $("#judulModal").html("Konfirmasi Barang Siap");
                content += '<a href="'+href+'" class="btn btn-success" id="btn-validasi">Barang Siap</a>';
            } else if(status == "siap" && level == "gudang"){
                $("#judulModal").html("Konfirmasi Barang Dikirim");
                content += '<a href="'+href+'" class="btn btn-success" id="btn-validasi">Konfirmasi Barang Dikirim</a>';
            } else if(status == "dikirim" && level == "spg"){
                $("#judulModal").html("Konfirmasi Barang Dikirim");
                content += '<a href="'+href+'" class="btn btn-success" id="btn-validasi">Konfirmasi Barang Diterima</a>';
            }
            else{
                $("#judulModal").html("Detail Permintaan");
                content = '';
            }


            $('#tombol-modal').html(content);

            $('#btn-validasi').on('click', function(e){
                e.preventDefault();
                const href = $(this).attr('href');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan bisa mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28A745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, barang sesuai!'
                    }).then((result) => {
                    if (result.value) {
                        document.location.href = href;
                    }
                })
            });

            $('#btn-tidakvalid').on('click', function(e){
                e.preventDefault();
                const href = $(this).attr('href');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Anda tidak akan bisa mengembalikan ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28A745',
                    cancelButtonColor: '#d33',
                    input: 'textarea',
                    inputPlaceholder: 'Keterangan...',
                    confirmButtonText: 'Ya, barang tidak sesuai!'
                    }).then((result) => {
                    if (result.value) {
                        document.location.href = href+'/'+result.value;
                    }
                })
            });

        })
    })
</script>
@endsection
