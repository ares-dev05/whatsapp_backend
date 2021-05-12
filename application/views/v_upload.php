<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<title>Upload dengan Codeigniter dan Ajax</title>
	<link rel="stylesheet" href="<?=base_url();?>assets/bootstrap-4/css/bootstrap.min.css">
</head>
<body>
	<div class="container pt-4">
        <h1 class="h3 text-center">Upload dengan Codeigniter dan Ajax</h1>
        <p class="small text-center">by <a href="https://simplecodz.blogspot.com">SimpleCodz</a></p>
		
		<div class="pt-4">
			<button onclick="add()" class="btn btn-sm btn-primary">Add New</button>
			<button onclick="load_galeri()" class="btn btn-sm btn-secondary">Reload</button>
		</div>

		<div id="galeri" class="row pt-4"></div>

	</div>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-dialog-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Modal</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="form">
					<input type="hidden" name="id">
					<div class="modal-body">
						<div class="form-group text-center" id="preview"></div>
						<div class="form-group">
							<div class="custom-file">
								<input type="file" name="file" id="file" class="custom-file-input">
								<label class="custom-file-label" for="file">
									Pilih Gambar
								</span>
							</div>
						</div>
						<div class="form-group">
							<input type="text" name="judul" class="form-control" placeholder="Judul">
						</div>
						<div class="form-group" id="fail_upload_msg"></div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-primary" id="btn_upload" type="submit">Upload</button>
					</div>
				</form>
			</div>
		</div>
	</div>

    <script src="<?=base_url();?>assets/jquery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="<?=base_url();?>assets/popper/popper.min.js"></script>
    <script src="<?=base_url();?>assets/bootstrap-4/js/bootstrap.min.js"></script>
    <script src="<?=base_url();?>assets/sweetalert2/sweetalert2.all.min.js"></script>

	<script type="text/javascript">
		var save_label;

		function load_galeri() {
			$.ajax({
				url: "<?=base_url()?>index.php/upload/get_data",
				type: "GET",
				dataType: "JSON",
				success: function(result){
					var url = '<?=base_url()?>/assets/images/';
					var img = '';
					var i = 1;
					$('#galeri').html('');

					$.each(result, function(key, val){
						img = 	`<div class="col-6 col-md-2 mb-4">
									<div class="card">
										<div>
											<img class="card-image-top h-auto w-100" src="${url + val.gambar}"/>
											<span style="left:5px;top:5px;" class="position-absolute badge badge-info">${i++}</span>
										</div>
										<div class="card-body p-2">
											<span class="small" style="font-weight: 500">${val.judul}</span>
											<div class="btn-group mt-2 d-flex">
												<button onclick="edit(${val.id})" class="w-100 btn btn-sm btn-outline-primary">Edit</button>
												<button onclick="hapus(${val.id})" class="w-100 btn btn-sm btn-outline-danger">Hapus</button>
											</div>
										</div>
									</div>
								</div>`;

						$('#galeri').append(img);
					});
				}
			});
		}

		function add(){
			save_label = 'add';
			$('#myModal').modal('show');
			$('#myModal .modal-title').text('Add Image');
		}

		function edit(id){
			save_label = 'edit';
			$('#myModal').modal('show');
			$('#myModal .modal-title').text('Edit Image');

			$.ajax({
				url: "<?=base_url()?>index.php/upload/get_single/"+id,
				type: "GET",
				dataType: "JSON",
				success: function(result){
					$('[name="id"]').val(result.id);
					$('[name="judul"]').val(result.judul);
					var path = '<?=base_url()?>assets/images/';
					var img = `<img src="${path + result.gambar}" class="rounded img-thumbnail" style="max-height: 75px;width: auto;"/>`;
					$('#preview').html(img);
				}
			});
		}

		function readUrl(input){
			if(input.files && input.files[0]){
				var reader = new FileReader();

				reader.onload = function(e){
					var img = `<img src="${e.target.result}" class="rounded img-thumbnail" style="max-height: 75px;width: auto;"/>`;
					$('#preview').html(img);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		$(document).ready(function(){
			load_galeri();

			$('[name="file"]').on('change', function(){
				readUrl(this);
			});

			$('#form').submit(function(e){
				e.preventDefault(); 
				e.stopPropagation();
				url = '';

				$('#btn_upload').attr('disabled','disabled').text('Tunggu...');
				
				if(save_label == 'add'){
					url = '<?php echo base_url();?>index.php/upload/do_upload';
				}else if(save_label == 'edit'){
					url = '<?php echo base_url();?>index.php/upload/edit';
				}

				$.ajax({
					url: url,
					type:'POST',
					data:new FormData(this),
					processData:false,
					contentType:false,
					cache:false,
					async:false,
					success: function(data){
						if(data.status){
							$('#myModal').modal('hide');
							load_galeri();
							swalert('disimpan');
						}else{
							console.log(data);
							var error;

							if(data.errors.judul){
								error = `<p class="invalid-feedback">${data.errors.judul}</p>`;
								$('[name="judul"]').addClass('is-invalid').after(error);
							}

							if(data.errors.file){
								error = `<p class="invalid-feedback">${data.errors.file}</p>`;
								$('[name="file"]').addClass('is-invalid').after(error);
							}

							if(data.errors.fail_upload){
								error = `<div class="alert alert-danger">
											${data.errors.fail_upload}
										</div>`;
								$('#fail_upload_msg').html(error);
							}
						}
						$('#btn_upload').removeAttr('disabled','disabled').text('Upload');
					}
				});
			});

			$('#myModal').on('hidden.bs.modal', function (e) {
				$('#form').trigger('reset');
				$('[name="id"]').val('');
				$('#preview').html('');
				$('#fail_upload_msg').html('');
				$('#form').find('input').removeClass('is-invalid');
				$('#form').find('p.invalid-feedback').remove();
			});
		});

		function swalert(method){
			Swal({
				title: 'Success',
				text: 'Data berhasil '+method,
				type: 'success'
			});
		};

		function hapus(id)
		{
		    Swal({
		        title: 'Anda Yakin?',
		        text: "Data akan dihapus permanen!",
		        type: 'warning',
		        showCancelButton: true,
		        confirmButtonColor: '#3085d6',
		        cancelButtonColor: '#d33',
		        confirmButtonText: 'Hapus data!'
		    }).then((result) => {
		        if(result.value) {
		            $.ajax({
		                url : "<?=base_url()?>index.php/upload/hapus/"+id,
		                type: "POST",
		                success: function()
		                {
							load_galeri();
		                    swalert('dihapus');
		                },
		                error: function (jqXHR, textStatus, errorThrown)
		                {
		                    alert('Error deleting data ' + id);
		                }
		            });
		        }
		    });
		}
	</script>
</body>
</html>
