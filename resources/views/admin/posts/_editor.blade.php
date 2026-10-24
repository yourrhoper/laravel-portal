@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-ru-RU.min.js"></script>
<script>
$(function () {
    // визуальный редактор для поля «Текст»
    $('#content').summernote({
        lang: 'ru-RU',
        height: 400,
        callbacks: {
            onImageUpload: function (files) {
                for (let i = 0; i < files.length; i++) {
                    uploadImage(files[i]);
                }
            }
        }
    });

    // загрузка картинки на сервер и вставка её адреса в текст
    function uploadImage(file) {
        const data = new FormData();
        data.append('image', file);
        data.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route('posts.upload-image') }}',
            method: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#content').summernote('insertImage', res.url, function ($img) {
                    $img.css('max-width', '100%');
                });
            },
            error: function (xhr) {
                alert(xhr.responseJSON?.message ?? 'Не удалось загрузить изображение');
            }
        });
    }

    // показать имя выбранного файла в поле custom-file
    $('.custom-file-input').on('change', function () {
        $(this).next('.custom-file-label').text(this.files[0] ? this.files[0].name : 'Выберите файл');
    });
});
</script>
@endpush