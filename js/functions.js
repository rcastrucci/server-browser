function submitForm() {
	// Get elements
	let form = document.getElementById("login_form");
	let pass = document.getElementById("user_pass");
	
    // Send submission
    pass.value = hex_sha256(pass.value);
    form.submit();
}


const buildingMsg = document.querySelectorAll('[building-msg]');

/* TOAST MESSAGES */
buildingMsg.forEach(element => element.addEventListener('click', () => {
    let msg;
    switch (element.getAttribute('building-msg')) {
        case 'download':
            msg = 'Aguarde! O download será iniciado automáticamente. Isto poderá levar alguns minutos.';
            break;
        default:
            msg = 'Aguarde enquanto estamos processando';
    }
    document.querySelectorAll('div.toast-body').forEach(div => div.innerHTML = msg);
    new bootstrap.Toast(document.getElementById('toast')).show();
}));