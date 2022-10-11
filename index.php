<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Popin PaymentForm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/style.css">

</head>

<body>
    <div class="List-Product" id="List-Product">
        <div class="Product">
            <h6>izi Jr</h6><img src="https://www.izipay.pe/_nuxt/dist/img/izi-jr-large.1272137.png" alt="izi Jr">
            <input type="hidden" id="amount" value="90">
            <p><span>S/</span>90</p><button>Comprar</button>
        </div>
        <div class="Product">
            <h6>izi android</h6><img src="https://www.izipay.pe/_nuxt/dist/img/izi-android-large.15bbbeb.png" alt="izi android">
            <input type="hidden" id="amount" value="100">
            <p><span>S/</span>100</p><button>Comprar</button>
        </div>
        <div class="Product">
            <h6>Gestiona tu negocio</h6><img src="https://www.izipay.pe/_nuxt/dist/img/img-pos.8c27182.png" alt="Gestiona tu negocio">
            <input type="hidden" id="amount" value="250">
            <p><span>S/</span>250</p><button>Comprar</button>
        </div>
        <div class="Product">
            <h6>Agente Izipay</h6><img src="https://www.izipay.pe/_nuxt/dist/img/agente-izipay-large.74b5825.png" alt="Agente Izipay">
            <input type="hidden" id="amount" value="200">
            <p><span>S/</span>200</p><button>Comprar</button>
        </div>
    </div>

    <?php
    if (isset($_POST["product"])) {
        $_SESSION["product"] = $_POST['product'];
        echo "
            <div class='content-checkout'>
                <div class='cart'>
                    <div class='Product'>
                        <h6>" . $_POST['product'] . "</h6><img src=" . $_POST["image"] . " alt=" . $_POST["image"] . ">
                        <p><span>S/</span>" . $_POST["amount"] . "</p>
                    </div>
                </div>
                <div class='checkout'>
                    <h5>Datos del cliente</h5>
                    <form id='form-control' method='post'> 
                        <input type='hidden' value=" . $_POST["amount"] . " />
                        <input type='hidden' value=" . $_POST["image"] . " />
                        <div class='control-group'>
                            <label for='firstname'>First Name</label>
                            <input type'text' id='firstname' name='firstname' autocomplete='off' required='' value=''>
                        </div>
                        <div class='control-group'>
                            <label for='lastname'>Last Name</label>
                            <input type'text' id='lastname' name='lastname' autocomplete='off' required='' value=''>
                        </div>
                        <div class='control-group'>
                            <label for='email'>Email</label>
                            <input type'emai' id='email' name='email' autocomplete='off' required value=''>
                        </div>
                        <button>Registrar</button>
                    </form>
                </div>
            </div>
            <script> 
            window.scroll({top:400,left:100,behavior:'smooth'})
            document.getElementById('form-control').addEventListener('click',(e)=>{
            if(e.target.nodeName == 'INPUT'){
                let group = e.target.parentElement.children;
                group[0].style.top = '-5px';
                group[0].style.fontSize = '12px';
                e.target.addEventListener('blur',(e)=> {
                    if(group[1].value.length === 0){
                        group[0].style.top = '12px';
                        group[0].style.fontSize = '16px';
                    }
                })
            }
            document.getElementById('form-control').addEventListener('submit',(e)=> infoPayment(e))
        })</script>
            ";
    } else {
    }
    ?>


    <script>
        const sendData = (path, parameters, method = 'post') => {
            const form = document.createElement('form');
            form.method = method;
            form.action = path;
            document.body.appendChild(form);

            for (const key in parameters) {
                const formField = document.createElement('input');
                formField.type = 'hidden';
                formField.name = key;
                formField.value = parameters[key];
                form.appendChild(formField);
            }
            form.submit();
        }
        document.getElementById("List-Product").addEventListener("click", (e) => {
            if (e.target.nodeName == "BUTTON") {
                let data = {
                    product: e.target.parentElement.children[0].innerText,
                    image: e.target.parentElement.children[1].src,
                    amount: e.target.parentElement.children[2].value,
                }
                console.log(data);
                e.target.parentElement.parentElement.outerHTML = "";
                // sendData('infoPayment.php', data, "post");
                sendData('index.php', data, "post");
            }
        })

        const infoPayment = (e) => {
            e.preventDefault();
            let product = document.querySelector(".content-checkout > .cart > .Product > h6").innerHTML;
            let dataPayment = {
                amount: e.target.children[0].value,
                image: e.target.children[1].value,
                firstName: e.target.children[2].children[1].value,
                lastName: e.target.children[3].children[1].value,
                email: e.target.children[4].children[1].value,
                product
            }
            sendData('payment.php', dataPayment, "post");
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

</body>

</html>