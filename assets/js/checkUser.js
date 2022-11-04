checkUser = new XMLHttpRequest();
const user_id = localStorage.getItem('user_id');
checkUser.open('POST', `../assets/php/index.php?do=checkUser&user_id=${user_id}`);

checkUser.addEventListener('load', () => {
    if (checkUser.status === 200) {
        data = JSON.parse(checkUser.response);
        if (data.ok === true && data.code === 200) {

        }else{
            window.location.href = "../login/";
        }
    }
})
checkUser.send();