<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<style>
    .fade {
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }
    .video-show {
        display: none
    }
</style>
<body>
<div class="container">

    <div class="card">
        <div class="card-body">
            <label class="form-label" for="customFile">Tải video theo từng đoạn</label>
            <input type="file" class="form-control" id="customFile" />

        </div>
        <div class="card-footer">
            <div class="progress" role="progressbar" aria-label="Success example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar bg-success fade" id="" style="width: 0%">0%</div>
              </div>
        </div>
        <video class="w-100 video-show" controls loop>
            <source type="video/mp4" />
          </video>
      </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.0.3/resumable.min.js" integrity="sha512-OmtdY/NUD+0FF4ebU+B5sszC7gAomj26TfyUUq6191kbbtBZx0RJNqcpGg5mouTvUh7NI0cbU9PStfRl8uE/rw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const file = document.getElementById('customFile');
    let fileExtension = '';
    file.addEventListener('change', function(e) {
        fileExtension = e.target.files[0].type;
    })
    var r = new Resumable({
        target:'{!! route('upload-video-chunk.upload') !!}',
        query:{_token:'{!! csrf_token() !!}','extension':fileExtension},
        //fileType:['mp4'],
        headers: {
            'Accept':'application/json',
        },
        testChunks:false,
    });
    r.assignBrowse(file);

    r.on('fileAdded', function(file, event){
        showProcess();
        r.upload();
      });
    r.on('fileSuccess', function(file, message){
        const res = JSON.parse(message);
        const video = document.querySelector('.video-show source').setAttribute('src',res.path)
        document.querySelector('.video-show').style.display = 'block'
        hideProcess();
       console.log('đã tải thành công');
      });
    r.on('fileError', function(file, message){
        console.log('đã bị lỗi khi tải');
      });
    r.on('fileProgress', function(file, message){
        updateProcess(Math.floor(file.progress() * 100));
    });

    function showProcess() {
        const progressBar = document.querySelector('.progress-bar');
        progressBar.style.width = `0%`;
        progressBar.html = 0;
        progressBar.style.opacity = 1;
    }
    function updateProcess(value) {
        const progressBar = document.querySelector('.progress-bar');
        progressBar.style.width = `${value}%`;
        progressBar.innerText = `${value}%`;
    }
    function hideProcess() {
        const progressBar = document.querySelector('.progress-bar');
        progressBar.style.width = `0%`;
        progressBar.html = 0;
        progressBar.style.opacity = 0;
    }
    hideProcess();
</script>
</body>
</html>