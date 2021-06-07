// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict';

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    let forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated')
            }, false)
        })
})();

function onchangephoto() {

    let photo_for_cropp = document.querySelector('#photo_for_cropp');
    photo_for_cropp.src = document.querySelector('#inputFile').value[0];

    let modal = new bootstrap.Modal(document.getElementById('modal'), {
        keyboard: false
    });
    modal.show();
}
