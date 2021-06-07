function attachfile(e) {

    e.preventDefault();

    let input = document.querySelector('#inputFile');
    input.click();

}

document.querySelector('#user_photo').addEventListener('click', attachfile);