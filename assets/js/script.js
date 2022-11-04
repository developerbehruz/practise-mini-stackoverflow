getAllQuestion = new XMLHttpRequest();
getAllQuestion.open('POST', './assets/php/index.php?do=getAllQuestion');

getAllQuestion.addEventListener('load', () => {
    if (getAllQuestion.status === 200) {
        data = JSON.parse(getAllQuestion.response);
        if (data.ok === true && data.code === 200) {
            data.result.forEach((item) => {
                document.querySelector('.wrapper').innerHTML += `
                    <div class="card">
                        <div class="card-head">
                            <h2>${item.name}</h2>
                        </div>
                        <div class="card-body">
                            <h3 class="title">${item.title}</h3>
                            <p class="desc">${item.question}</p>
                            <b>${item.date}</b>
                        </div>
                        <div class="card-footer">
                            <p></p>
                            <div>
                            <button class="viewAns" id="${item.user_id}q_id=${item.id}">View answers</button>
                            <button class="questionBtn" id="${item.user_id}q_id=${item.id}">Write answer</button>
                            </div>
                        </div>
                    </div>
                `;
            });
            let modal = document.querySelector('.modal'),
                modalAns = document.querySelector('.modalAns'),
                main = document.querySelector('main'),
                close = document.querySelector('.close'),
                questionBtn = document.querySelectorAll('.questionBtn'),
                viewAns = document.querySelectorAll('.viewAns');

            viewAns.forEach((item) => {
                item.addEventListener('click', () => {
                    modalAns.style.display = "flex";
                    close.style.display = "block";
                    main.style.opacity = 0.1;

                    let id = item.id.split('q_id='),
                        modalDiv = document.querySelector('.modalDiv');

                    getAllAnswersReq = new XMLHttpRequest();
                    getAllAnswersReq.open('POST', `./assets/php/index.php?do=getAllAnswers&q_user_id=${id[0]}&q_id=${id[1]}`);

                    getAllAnswersReq.addEventListener('load', () => {
                        if (getAllAnswersReq.status === 200) {
                            data = JSON.parse(getAllAnswersReq.response);
                            if (data.ok === true && data.code === 200) {
                                console.log(data);
                                if (data.result.length == 0) {
                                    modalDiv.innerHTML = "<h4 style='color:#fff;'>No one has replied yet!</h4>"
                                }else{
                                    data.result.forEach((items) => {
                                        modalDiv.innerHTML += `
                                        <div class="card">
                                            <div class="card-head">
                                                <h2>${items.a_name}</h2>
                                            </div>
                                            <div class="card-body">
                                                <p class="desc">${items.answers}</p>
                                                <b>${items.date}</b>
                                            </div>
                                        </div>
                                        `;
                                    })
                                }
                            }
                            console.log(getAllAnswersReq);
                        }
                    });
                    getAllAnswersReq.send();
                });
            })
            questionBtn.forEach((item) => {
                item.addEventListener('click', () => {
                    modal.style.display = "flex";
                    close.style.display = "block";
                    main.style.opacity = 0.1;

                    let user_id = localStorage.getItem('user_id'),
                        id = item.id.split('q_id='),
                        answersForm = document.querySelector('.answersForm'),
                        answersBtn = document.querySelector('.answersBtn'),
                        msgAnswers = document.querySelector('.msgAnswers'),

                    addAnswerReq = new XMLHttpRequest();
                    addAnswerReq.open('POST', `./assets/php/index.php?do=addAnswers&q_user_id=${id[0]}&a_user_id=${user_id}&q_id=${id[1]}`);

                    answersBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        addAnswerReq.addEventListener('load', () => {
                            if (addAnswerReq.status === 200) {
                                data = JSON.parse(addAnswerReq.response);
                                if (data.ok === true && data.code === 200) {
                                    msgAnswers.innerHTML = `<h5 style="color:green;text-align:center">${data.message}</h5>`;

                                    setTimeout(() => {
                                        modal.style.display = "none";
                                        close.style.display = "none";
                                        main.style.opacity = 1;
                                    }, 2000);
                                }else{
                                    msgAnswers.innerHTML = `<h5 style="color:red;text-align:center">${data.message}</h5>`;
                                }
                            }
                        });
                        setTimeout(() => {
                            msgAnswers.innerHTML = "";
                            document.querySelector('.textarea').value = "";
                            
                        }, 2000);
                        let formData = new FormData(answersForm)
                        addAnswerReq.send(formData);
                    });
                });
            });

            close.addEventListener('click', () => {
                modal.style.display = "none";
                modalAns.style.display = "none";
                close.style.display = "none";
                main.style.opacity = 1;
                document.querySelector('.modalDiv').innerHTML = "";
            })
        }
    }
})

getAllQuestion.send();