signup = (el) => {

    $fn=document.querySelector("#first-name")
    $ln=document.querySelector("#last-name")
    $ea=document.querySelector("#Email\\ Address")
    $pass=document.querySelector("#password")
    $cn=document.querySelector("#childname")
    $cp=document.querySelector("#childphone")
    $cpass=document.querySelector("#childpassword")
    var data = JSON.stringify({
        "name": $fn.value + " "+ $ln.value,
        "phone": $ea.value,
        "password": $pass.value,
        "child_account": {
            "name": $cn.value,
            "phone": $cp.value,
            "password": $cpass.value,
        }
        });

        var xhr = new XMLHttpRequest();
        xhr.withCredentials = true;

        xhr.addEventListener("readystatechange", function() {
        if(this.readyState === 4) {
            response = JSON.parse(this.responseText);
            if (response.success) {
                alert('Sign up successfully')
                window.location.assign('/login.html')
            } else {
                alert('There Error, message: ' + response.message)

            }
            console.log(this.responseText);
        }
        });

        xhr.open("POST", "/api/auth/register.php");
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.send(data);
}