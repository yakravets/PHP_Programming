function onchangephoto() {

    let photoUrlValue = document.querySelector('#inputFile').files;
    if(photoUrlValue.length > 0)
    {
        let photo_for_cropp = document.querySelector('#photo_for_cropp');
        const cropper = new Cropper(photo_for_cropp, {
            aspectRatio: 1 / 1,
            preview: ".preview"
        });

        let reader  = new FileReader();
        reader.onload = function(event)
        {
            let data = event.target.result;
            let modal = new bootstrap.Modal(document.getElementById('modal'), {
                keyboard: false
            });
            modal.show();

            cropper.replace(data);
        };

        reader.readAsDataURL(photoUrlValue[0]);

        document.querySelector('#btnCropped').addEventListener('click', function (){

            let dataCropper = cropper.getCroppedCanvas().toDataURL();
            document.querySelector('#photo_for_cropp').src = dataCropper;
            document.querySelector('#inputFile').value = dataCropper;
            let modal = new bootstrap.Modal(document.getElementById('modal'), {
                keyboard: false
            });
            modal.hide();
        });
    }
}

function attachfile(e) {
    e.preventDefault();
    let input = document.querySelector('#inputFile');
    input.click();
}

document.querySelector('#user_photo').addEventListener('click', attachfile);
document.querySelector('#inputFile').addEventListener('change', onchangephoto);