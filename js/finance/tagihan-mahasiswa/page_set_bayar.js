const load_default = () => {

}

const key_press_nim = async(nim) => {

}


$(document).on('keypress','#NIM', function (event)
{
    if (event.keyCode == 10 || event.keyCode == 13) {
      valuee = $(this).val();
      key_press_nim(valuee);
    }
}); // exit enter


