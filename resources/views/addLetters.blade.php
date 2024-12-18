<div class="row">
	<div class="col-md-12">	
		{!! Form::model($model,[
			'route' => 'store',
			'method' => 'POST',
			'files' => true,
			'id' => 'request_form'
		]) !!}	
            <div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Title</label>
				</div>
				<div class="col-md-8">
					<input type="text" name="title" id="title" class="form-control" placeholder="Title" value="">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Attachment</label>
				</div>
				<div class="col-md-8">
					<input type="number" id="attachment" name="attachment" class="form-control" placeholder="Attachment" min="0" value="0">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Sender Name</label>
				</div>
				<div class="col-md-8">
					<input type="text" name="sender_name" id="sender_name" class="form-control" placeholder="Sender Name" value="">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Recepient Name</label>
				</div>
				<div class="col-md-8">
					<input type="text" name="recipient_name" id="recipient_name" class="form-control" placeholder="Recepient Name" value="">
				</div>
			</div>
			<div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Recepient Address</label>
				</div>
				<div class="col-md-8">
					<textarea rows="2" name="recipient_address" id="recipient_address" class="form-control" placeholder="Recepient Address"></textarea>
				</div>
			</div>
            <div class="form-group row mb-2">
				<div class="col-md-4">
					<label class="control-label">Content</label>
				</div>
				<div class="col-md-8">
					<div id="editor">
						<p>Hello from CKEditor 5!</p>
					</div>
					<textarea name="content" id="content" style="display: none;"></textarea>
				</div>
			</div>
            <div id='loadingmessage' class="col-md-12 mt-2 text-center" style="display: none;">
				<img src="{{ asset('images/spinner-mini.gif') }}"/> Please wait
			</div>
			<div class="text-right d-flex justify-content-end">
				<button type="submit" class="btn btn-primary btn-sm" id="submit_btn">CREATE</button>
			</div>
		{!! Form::close() !!}
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#request_form').on('submit', function(e) {
        e.preventDefault();

        var form = $('#request_form'),
            url = form.attr('action'),
            modalAdd = bootstrap.Modal.getInstance(document.getElementById('modal_large'));

        form.find('.invalid-feedback').remove();
        form.find('.form-control').removeClass('is-invalid');
        $('#loadingmessage').show();
        $("#submit_btn").hide();

        $.ajax({
            url: url,
            method: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(returnData) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    // text: 'Data has been successfully submitted!',
                    allowOutsideClick: false
                }).then(function() {
                    modalAdd.hide();
                    $('#data-letter').DataTable().ajax.reload(); // Reload DataTable
                });

                $('#loadingmessage').hide();
                $("#submit_btn").show();
            },
            error: function(xhr) {
                var res = xhr.responseJSON;
                if ($.isEmptyObject(res) == false) {
                    $.each(res.errors, function(key, value) {
                        $('#' + key).closest('.form-control').addClass('is-invalid');
                    });
                }

                $('#loadingmessage').hide();
                $("#submit_btn").show();
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
        // Sync CKEditor content to the hidden textarea before form submit
        document.getElementById('request_form').addEventListener('submit', () => {
            document.getElementById('content').value = editor.getData();
        });
    })
    .catch(error => {
        console.error('Error initializing CKEditor:', error);
    });

</script>