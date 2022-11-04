let addQForm = document.querySelector('.addQForm'),
    addQBtn = document.querySelector('.addQBtn'),
    msgAQues = document.querySelector('.msgAQues');

addQBtn.addEventListener('click', (event) => {
    event.preventDefault();
    addQuesReq = new XMLHttpRequest();
    const user_id = localStorage.getItem('user_id');
    addQuesReq.open('POST', `../assets/php/index.php?do=addQuestion&user_id=${user_id}`);

    addQuesReq.addEventListener('load', () => {
        if (addQuesReq.status === 200) {
            data = JSON.parse(addQuesReq.response);
            if (data.ok === true && data.code === 200) {
                msgAQues.innerHTML = `<h4 style='color:green; text-align:center;'>${data.message}</h4>`;

                localStorage.setItem('user_id', data.result[0].user_id);
            }else{
                msgAQues.innerHTML = `<h4 style='color:red; text-align:center;'>${data.message}</h4>`;
            }
        }
    });

    setTimeout(() => {
        msgAQues.innerHTML = "";
        document.querySelector('.titleQ').value = "";
        document.querySelector('.questionQ').value = "";
    }, 2000);

    let formData = new FormData(addQForm);
    addQuesReq.send(formData);
});