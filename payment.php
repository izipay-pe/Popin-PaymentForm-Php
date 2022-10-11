<?php
require_once "IzipayController.php";
require_once "keys.php";

date_default_timezone_set("UTC");

$payment = new IzipayController();

$datos = array(
  "amount" => $_POST["amount"]*100,
  "currency" => "PEN",
  "customer" => array(
    "email"=>$_POST["email"],
  ),
  "orderId" => uniqid("MyOrderId"),
);
$response = $payment->post("V4/Charge/CreatePayment",$datos);
$formToken = $response["answer"]["formToken"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Redirect Form Izipay</title> 

  <!-- Javascript library. Should be loaded in head section -->
  <script 
   src="<?= $payment->getEndpointApiRest() ?>/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
   kr-public-key="<?= $payment->getPublicKey() ?>"
   kr-post-url-success="paid.php">
  </script>
  <!-- theme and plugins. should be loaded after the javascript library -->
  <!-- not mandatory but helps to have a nice payment form out of the box -->
  <link rel="stylesheet" 
  href="<?= $payment->getEndpointApiRest() ?>/static/js/krypton-client/V4.0/ext/classic-reset.css">
 <script 
  src="<?= $payment->getEndpointApiRest() ?>/static/js/krypton-client/V4.0/ext/classic.js">
 </script> 
  
  
  <link rel="stylesheet" href="styles/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />

  <style>
    .kr-popin-utils{
        display: none;
    }
    .kr-popin-utils:hover{
        transform: scale(1.1);
    }
  </style>
  
</head>
<body>
      <div class="content-checkout">
        <div class='cart'>
          <div class='Product'>
              <h5><?=$_POST['product']?></h5><img src="<?=$_POST['image']?>" alt="<?=$_POST['image']?>">
              <p><span>S/</span><?=$_POST['amount']?></p>
          </div>
        </div>
        <div class='checkout'>
          <h5>Datos del cliente</h5>
          <form action="" method="post">
              <div class='control-group'>
                  <input type="text" name="name" value="<?=$_POST["firstName"] ?>" disabled>
              </div>
              <div class='control-group'>
                  <input type="text" value="<?=$_POST["lastName"] ?>" disabled>
              </div>
              <div class='control-group'>
                  <input type='email' value="<?=$_POST["email"] ?>" disabled>
              </div>

              <button  type="submit" name="pagar">Confirmar</button>
          </form>

            <!-- payment form -->
          <div class="kr-embedded" kr-popin  kr-form-token="<?= $formToken ?>">

            <!-- payment form fields -->
            <div class="kr-pan"></div>
            <div class="kr-expiry"></div>
            <div class="kr-security-code"></div>  

            <!-- payment form submit button -->
            <button class="kr-payment-button"></button>
            <!-- error zone -->
            <div class="kr-form-error"></div>
          </div>  

        </div>
      </div>
    </div>
  </div>

 
  <script>
    
    window.onload = function() {
      document.querySelector(".checkout > form").addEventListener("submit",async (e)=>{
        e.preventDefault();
        handleDisplay(".checkout > form","none");
        handleDisplay(".checkout > .kr-popin-utils","block")
      })

      KR.onSubmit( function(event) {
        /* Change the button label to the orderStatus */
        if(sendPayment("validatePayment",event)){
          handleDisplay("div.cart","none");
          handleDisplay("div.checkout","none");
          showAnswer(event);
          // document.querySelector(".content-checkout").innerHTML = `<h2>Pago Finalizado</h2>`;

        }else{
          console.log("Error de pago");
        }
          /* return values:
        * true: kr-post-success-url is called using POST
        * false: kr-post-success-url is not called, execution stops.
        */
        return false;

      });

      const handleDisplay = (element,display)=>{
        document.querySelector(element).style.display = display;
      }

      const sendPayment = async (target, data) => {
        fetch(`${target}.php`,{
          method:"POST",
          headers:{"Content-Type":"application/json"},
          body: JSON.stringify(data)
        })
        .then(res=>res.json())
        .then(res => {
          if(res.rpta == "ok"){
            console.log("respuesta ipn", res);
            return true;
          }else{
            console.log(res.body);
            return false;
          }
        })
        .catch(err=>{
          console.log("Error:",err);
          return false;
        })
    
      }

      const showAnswer = (answer)=>{
        let html = "";
        let content = document.querySelector(".content-checkout");
        document.querySelector(".App > h2").innerHTML = "Pago Finalizado";
        const { clientAnswer, hash, hashAlgorithm, hashKey } = answer;
        html += field("hash", hash);
        html += field("hashAlgorithm", hashAlgorithm);
        html += field("hashKey", hashKey);
        html += field("clientAnswer", `<div class='kr-answer'>${JSON.stringify(clientAnswer)}</div>`);
        console.log(answer);
        // Object.entries(answer).forEach((key, value) => {
        //   html += `<p><span>${key}</span>${value}</p>`;
        // });
        content.outerHTML = html;
      }

      const field = (field, value) => `<p><span>${field} : </span>  ${value}</p>`;
      
    };
  </script>
</body>
</html>