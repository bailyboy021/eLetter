<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel - eLetter</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
        <script src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js"></script>
        <link href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />
        <script src="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.umd.js"></script>
    </head>
    <body class="antialiased">
        <div class="container relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
            <div class="row">
                <div class="col-md-10">
                    <div id="column_60_ecard">            
                        <div class="card">
                            <div class="card-body">
                                {!! Form::model($model,[
                                    'route' => $model->exists? ['update', $model->id] : 'store',
                                    'method' => $model->exists? 'PUT' : 'POST',
                                    'files' => true,
                                    'id' => 'updateLetter'
                                ]) !!}

									<input type="hidden" id="letterId" name="letterId" value="{{ $model->id }}">
									<div class="form-group row mb-2">
										<div class="col-md-4">
											<label class="control-label">Title</label>
										</div>
										<div class="col-md-8">
											<input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $model->title }}">
										</div>
									</div>
									<div class="form-group row mb-2">
										<div class="col-md-4">
											<label class="control-label">Attachment</label>
										</div>
										<div class="col-md-8">
											<input type="number" id="attachment" name="attachment" class="form-control" placeholder="Attachment" min="0" value="{{ $model->attachments }}">
										</div>
									</div>
									<div class="form-group row mb-2">
										<div class="col-md-4">
											<label class="control-label">Sender Name</label>
										</div>
										<div class="col-md-8">
											<input type="text" name="sender_name" id="sender_name" class="form-control" placeholder="Sender Name" value="{{ $model->sender_name }}">
										</div>
									</div>
									<div class="form-group row mb-2">
										<div class="col-md-4">
											<label class="control-label">Recepient Name</label>
										</div>
										<div class="col-md-8">
											<input type="text" name="recipient_name" id="recipient_name" class="form-control" placeholder="Recepient Name" value="{{ $model->recipient_name }}">
										</div>
									</div>
									<div class="form-group row mb-2">
										<div class="col-md-4">
											<label class="control-label">Recepient Address</label>
										</div>
										<div class="col-md-8">
											<textarea rows="2" name="recipient_address" id="recipient_address" class="form-control" placeholder="Recepient Address">{{ $model->recipient_address }}</textarea>
										</div>
									</div>
									<div class="form-group row mb-2">
										<div class="col-md-4">
											<label class="control-label">Content</label>
										</div>
										<div class="col-md-8">
											<div id="editor">
												{{ $model->content }}
											</div>
											<textarea name="content" id="content" style="display: none;">{!! $model->content !!}</textarea>
										</div>
									</div>
                                    
                                    <div class="form-group" id="load_page"></div>
                                    <div id='loadingmessage' class="form-group mt-2 text-center" style="display: none;">
                                        <img src="{{ asset('images/spinner-mini.gif') }}"/> Please wait
                                    </div>
                                    <div class="text-right d-flex justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-primary me-2 h-25" id="submit_btn">UPDATE</button>
										<a href="{{ route('print', $encrypt) }}" target="_blank" class="btn btn-sm mb-2 btn-info text-light h-25" title="Detail: {{ $model->letter_number }}">PRINT</a> 
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
				</div>
                <div class="col-md-2">
				</div>
            </div>
            @include('_modal')
        </div>
        
		<script type="text/javascript">
			$(document).ready(function(){
				
				$('#updateLetter').on('submit', function(e){
					e.preventDefault(); 
					var form = $('#updateLetter'),
						url = form.attr('action');        
						
						form.find('.form-control').removeClass('is-invalid');
						form.find('.alert_point').html('');
						form.find('#alert_project_member').attr('style', 'width:100%');
						$('#loadingmessage').show();
						$("#submit_btn").hide();

					$.ajax({
						url: url,
						method: "POST",  
						data: new FormData(this),  
						contentType: false,  
						cache: false,  
						processData:false,
						success:function(returnData)
						{  	
							Swal.fire({
                                icon: 'success',
                                title: 'Success',
							}).then(function() {
								location.reload();
							});
							$('#loadingmessage').hide();
							$("#submit_btn").show();
						},
						error: function (xhr, error, thrown) {
							Swal.fire({
								icon: 'error',
								title: 'Oops...',
								text: 'Something went wrong!',
							}).then(function(){
								location.reload();
							});
						}
					});  
				});
			});
		</script>
		<script>

			CKEDITOR.ClassicEditor
			.create(document.querySelector('#editor'), {
				plugins: [
					CKEDITOR.Essentials,
					CKEDITOR.Bold,
					CKEDITOR.Italic,
					CKEDITOR.Font,
					CKEDITOR.Paragraph,
					CKEDITOR.Alignment
				],
				alignment: {
					options: [
						{ name: 'left', className: 'my-align-left' },
						{ name: 'right', className: 'my-align-right' },
						{ name: 'center', className: 'my-align-center' },
						{ name: 'justify', className: 'my-align-justify' }
					]
				},
				toolbar: [
					'undo', 'redo', '|', 'bold', 'italic', '|',
					'alignment:left', 'alignment:center', 'alignment:right', 'alignment:justify'
				]
			})
			.then(editor => {
				// Pastikan CKEditor memuat data yang sudah ada
				editor.setData(`{!! addslashes($model->content) !!}`);

				// Sinkronisasi CKEditor dengan hidden textarea sebelum submit
				document.getElementById('updateLetter').addEventListener('submit', () => {
					document.getElementById('content').value = editor.getData();
				});
			})
			.catch(error => {
				console.error('Error initializing CKEditor:', error);
			});


		</script>
	</body>
</html>