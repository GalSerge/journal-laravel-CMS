<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset=UTF-8>
    <title>CKEditor</title>
</head>
<body>
<textarea name="content" id="content" cols="30" rows="10"></textarea>
<script src={{ asset('js/ckeditor/ckeditor.js') }}></script>
<script>
    CKEDITOR.replace( 'content', {
        filebrowserBrowseUrl: '{{ asset(route('ckfinder_browser')) }}',
        filebrowserImageBrowseUrl: '{{ asset(route('ckfinder_browser')) }}?type=Images',
        filebrowserUploadUrl: '{{ asset(route('ckfinder_connector')) }}?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '{{ asset(route('ckfinder_connector')) }}?command=QuickUpload&type=Images',
    });
</script>
@include('ckfinder::setup')
</body>
</html>