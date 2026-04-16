const endpoint = 'https://server.dropper.alexaat.com/serve.php';
//const endpoint = 'http://localhost:3000/serve.php';

const downloadEndpoint = endpoint + '?uid=';

const dropZone = document.querySelector('#drop_zone');
const downloadContainer = document.querySelector('#download_container');
const fileName = document.querySelector('#file_name');
const downloadLink = document.querySelector('#download_link');

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
      if(data.success){
        const file_name = data.file;
        const uid = data.uid;    
        generateQRcode(uid);
        fileName.innerText = file_name;
        downloadLink.innerText = downloadEndpoint + uid;
        downloadLink.setAttribute('href', downloadEndpoint + uid);
        downloadContainer.style.display = 'flex';  
      }
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


