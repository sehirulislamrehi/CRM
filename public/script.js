
document.getElementById('option_a1').addEventListener('click',function(e){
    var emailField=document.getElementById('emailField');
    var userField=document.getElementById('userField');
    console.log(e.target.value)
    if(e.target.value){
        if(!emailField.classList.contains('d-none')){
            emailField.classList.add('d-none');
        }
        if(userField.classList.contains('d-none')){
            userField.classList.remove('d-none');
        }
    }
});
document.getElementById('option_a2').addEventListener('click',function(e){
    var emailField=document.getElementById('emailField');
    var userField=document.getElementById('userField');
    console.log(e.target.value)
    if(e.target.value){
        if(!userField.classList.contains('d-none')){
            userField.classList.add('d-none');
        }
        if(emailField.classList.contains('d-none')){
            emailField.classList.remove('d-none')
        }
    }
});