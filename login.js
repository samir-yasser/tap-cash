login  = (el) => {
let phone = document.querySelector("body > div > div > div:nth-child(3) > input")
let password = document.querySelector("body > div > div > div:nth-child(6) > input")
var data = JSON.stringify({
  "phone": phone.value,
  "password": password.value
});

var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function() {
  if(this.readyState === 4) {
      let response = JSON.parse(this.responseText);
      if (response.success) {
          localStorage.setItem('token', response.token)
          window.location.assign('/dashboard.html')
      } else {
          alert('your username or password is wrong, please try again.')
      }
    console.log();
  }
});

xhr.open("POST", "http://smartwallet.byethost10.com/api/auth/login.php");
xhr.setRequestHeader("Content-Type", "application/json");

xhr.send(data);
}