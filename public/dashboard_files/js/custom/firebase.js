$(document).ready(function(){
   
   // For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDsa4gpUSWs1a1abFpj42mKxJ5q4-UjBGk",
  authDomain: "agell-99bc9.firebaseapp.com",
  projectId: "agell-99bc9",
  storageBucket: "agell-99bc9.appspot.com",
  messagingSenderId: "1023476900011",
  appId: "1:1023476900011:web:25396549819401a04857ee",
  measurementId: "G-4M7JJ3JYTN"
};


  // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
   onSignInSubmit();
    
});



function onSignInSubmit() {
    $('#statues').on('click', function() {
        alert('dd');
        let phoneNo = '';
        var code = $('#codeToVerify').val();
        console.log(code);
        $(this).attr('disabled', 'disabled');
        $(this).text('Processing..');
        confirmationResult.confirm(code).then(function (result) {
                    alert('Succecss');
            var user = result.user;
            console.log(user);
    
    
            // ...
        }.bind($(this))).catch(function (error) {
        
            // User couldn't sign in (bad verification code?)
            // ...
            $(this).removeAttr('disabled');
            $(this).text('Invalid Code');
            setTimeout(() => {
                $(this).text('Verify Phone No');
            }, 2000);
        }.bind($(this)));
    
    });
    
    
    $('#getcode').on('click', function () {
        var phoneNo = $('#number').val();
        console.log(phoneNo);
        // getCode(phoneNo);
        var appVerifier = window.recaptchaVerifier;
        firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
        .then(function (confirmationResult) {
    
            window.confirmationResult=confirmationResult;
            coderesult=confirmationResult;
            console.log(coderesult);
        }).catch(function (error) {
            console.log(error.message);
    
        });
    });
}
