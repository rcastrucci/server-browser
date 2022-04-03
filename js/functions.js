function submitForm() {
	// Get elements
	let form = document.getElementById("login_form");
	let pass = document.getElementById("user_pass");
	
    // Send submission
    pass.value = hex_sha256(pass.value);
    form.submit();
}

function setWindowContentSize(percentage, element, speed) {
    let vh = window.innerHeight - 190;
    const container = document.querySelectorAll('div.window-container');
    
    /* SET HEIGHT WITH PERCENTAGE */
    document.querySelectorAll('div.window-content').forEach(div => div.style.height = vh*percentage/102+'px');

    /* SET WIDTH */
    if (percentage === 100) {
        container.forEach(div => {
            div.removeAttribute('class');
            div.setAttribute('class', 'window-container col-12 m-0 p-0');
        });
    } else {
        container.forEach(div => {
            div.removeAttribute('class');
            div.setAttribute('class', 'window-container col-12 col-sm-12 col-md-9 col-lg-7 col-xl-6');
        });
    }

    if (speed > 0) {
        element.style.transition = "all 200ms";
        setTimeout(() => {
            let offset = centerElement(element);
            element.style.left = (offset[0]) + 'px';
            element.style.top  = (offset[1]) + 'px';
            setTimeout(() => {
                element.style.transition = '0s';
            }, speed);
        }, speed);
    }
}

function centerElement(element) {
    let elementWidth = element.offsetWidth;
    let elementHeight = element.offsetHeight;
    let windowWidth = window.innerWidth;
    let windowHeight = window.innerHeight;
    return [(windowWidth - elementWidth)/2, (windowHeight - elementHeight)/2];
}

const linksHref = document.querySelectorAll('[href]');
const urlGet = new URL(window.location.href);
const ms = urlGet.searchParams.get("ms");
const mt = urlGet.searchParams.get("mt");
const ws = urlGet.searchParams.get("ws");
const divWindowHead = document.querySelector('.window-head');
const divWindow = document.querySelector('.window-container');
const headReader = document.getElementById('headReader');
const windowReader = document.getElementById('windowReader');
let windowSize = 50;
let mousePosition;
let offset;
let isDown = false;
const btnClose = document.getElementById('btn_close');
const btnMin = document.getElementById('btn_min');
const btnMax = document.getElementById('btn_max');

const btnCloseReader = document.getElementById('btn_close_reader');
const btnMinReader = document.getElementById('btn_min_reader');
const btnMaxReader = document.getElementById('btn_max_reader');

const buildingMsg = document.querySelectorAll('[building-msg]');
const windowBtns = document.querySelectorAll('[data-hover]');
const sectionWindow = document.querySelector('div.row > section');

window.addEventListener('resize', function(event) {
    setWindowContentSize(windowSize, divWindow, 200);
}, true);

btnClose.onclick = () => {
    sectionWindow.classList.add('d-none');
}

btnMin.onclick = () => {
    setWindowContentSize(windowSize = 50, divWindow, 200);
}

btnMax.onclick = () => {
    if (windowSize === 50) windowSize = 90;
    else if (windowSize === 90) windowSize = 100;
    else windowSize = 50;
    setWindowContentSize(windowSize, divWindow, 200);
}

if (windowReader) {
    btnCloseReader.onclick = () => {
        windowReader.remove();
    }

    btnMinReader.onclick = () => {
        setWindowContentSize(windowSize = 50, windowReader, 200);
    }

    btnMaxReader.onclick = () => {
        if (windowSize === 50) windowSize = 90;
        else if (windowSize === 90) windowSize = 100;
        else windowSize = 50;
        setWindowContentSize(windowSize, windowReader, 200);
    }
}


/* WINDOW BUTTONS HOVER */
windowBtns.forEach(element => {
    let start = element.src;
    let hover = element.getAttribute('data-hover');
    element.onmouseover = () => { element.src = hover; }
    element.onmouseout  = () => { element.src = start; }
});

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

divWindowHead.addEventListener('mousedown', function(e) {
    isDown = true;
    if (headReader) {
        offset = [
            windowReader.offsetLeft - e.clientX,
            windowReader.offsetTop - e.clientY
        ];
    } else {
        offset = [
            divWindow.offsetLeft - e.clientX,
            divWindow.offsetTop - e.clientY
        ];
    }
}, true);

if (headReader) {
    headReader.addEventListener('mousedown', function(e) {
        isDown = true;
        offset = [
            windowReader.offsetLeft - e.clientX,
            windowReader.offsetTop - e.clientY
        ];
    }, true);
}

document.addEventListener('mouseup', function() {
    isDown = false;
}, true);

document.addEventListener('mousemove', function(event) {
    event.preventDefault();
    if (isDown) {
        mousePosition = {    
            x : event.clientX,
            y : event.clientY
        };
        if (headReader) {
            windowReader.style.left = (mousePosition.x + offset[0]) + 'px';
            windowReader.style.top  = (mousePosition.y + offset[1]) + 'px';
        } else {
            divWindow.style.left = (mousePosition.x + offset[0]) + 'px';
            divWindow.style.top  = (mousePosition.y + offset[1]) + 'px';
        }
    }
}, true);

/* PASS CORDENATE THRU URL GET */
linksHref.forEach(link => link.addEventListener('click', () => {
    link.href = link.getAttribute('href')+'&ms='+divWindow.style.left+'&mt='+divWindow.style.top+'&ws='+windowSize;
}));

/* ADJUST WINDOW */
if (ws != null) windowSize = parseInt(ws);
setWindowContentSize(windowSize, divWindow, 0);

if ((ms !== null) && (mt !== null)) {
    divWindow.style.left = ms;
    divWindow.style.top  = mt;
} else {
    /* As it is first time loading window frame it can not wait to load completly */
    sectionWindow.classList.remove('d-none');
    offset = centerElement(divWindow);
    divWindow.style.left = (offset[0]) + 'px';
    divWindow.style.top  = (offset[1]) + 'px';
}

window.onload = () => {
    /* Waits to load html to avoid frame window blink while javascript is changing and adjusting its position */
    sectionWindow.classList.remove('d-none');
}