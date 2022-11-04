let sign = document.querySelector('.signForm'),
    signBtn = document.querySelector('.signBtn'),
    msgSign = document.querySelector('.msgSign');

signBtn.addEventListener('click', (event) => {
    event.preventDefault();
    signReq = new XMLHttpRequest();
    signReq.open('POST', '../assets/php/index.php?do=signup');

    signReq.addEventListener('load', () => {
        if (signReq.status === 200) {
            data = JSON.parse(signReq.response);
            if (data.ok === true && data.code === 200) {
                msgSign.innerHTML = `<h4 style='color:green; text-align:center;'>${data.message}</h4>`;

                localStorage.setItem('user_id', data.result[0].user_id);

                setTimeout(() => {
                    window.location.href = "../cabinet/";
                    msgSign.innerHTML = "";
                }, 2000);
            }else{
                msgSign.innerHTML = `<h4 style='color:red; text-align:center;'>${data.message}</h4>`;

                setTimeout(() => {
                    msgSign.innerHTML = "";
                }, 2000);
            }
        }
    })
    let formData = new FormData(sign);
    signReq.send(formData);
});