const endpoint = 'http://localhost:3000/serve.php';

const dropZone = document.querySelector('#drop_zone');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => {
   dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    const files = e.dataTransfer.files;
    if (files.length > 0) {
      uploadFile(files[0]);
    }
});


function uploadFile(file){
    const formData = new FormData();
    formData.append('file', file);

    fetch(endpoint, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
      console.log('Upload success:', data);
      alert('File uploaded successfully!');
      const fileName = data.file;
      const url = data.url;
      console.log('file name: ', fileName, 'url: ', url);
      generateQRcode(url);

    })
    .catch(error => {
      console.error('Upload error:', error);
      alert('Upload failed!');
    });
}

function generateQRcode(text){
    const qrcode = new QRCode(document.getElementById('qrcode'), { 
        text,
        width: 128,
        height: 128,
        colorDark : '#000',
        colorLight : '#fff',
        correctLevel : QRCode.CorrectLevel.H
    });
}


