<!doctype html>
<html lang=”fr”>
<head>
    <meta charset=”utf-8">
    <meta http-equiv=”X-UA-Compatible” content=”IE=edge”>
    <meta name=”viewport” content=”width=device-width, initial-scale=1">
    <meta name="viewport" content="maximum-scale=1">

    <!-- styles -->

    <style type="text/css">
        .layout{
            position: relative;
            background-color: transparent;
            height: 100vh;
            overflow-x: hidden !important;
        }
        .layout:before{
             position: absolute;
             width: 100vw;
             height: 100vh;
             content: " ";
             filter: blur(5px);
             overflow-y: hidden;
             background-repeat: no-repeat;
             background-position: center;
             background-size: cover;
         }

        .maintenance {
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            background-image: url("/assets/bg/BG_2.jpg");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        .card {
            margin: auto auto;
            background-color: #0c2646;
            border-radius: 30px;
            padding: 10px;
            display: flex;
            flex-direction: column;
        }
        h1{
            text-align: center;
            font-size: 40px;
            font-weight: 900;
            color: #00FFFF;
        }

        .contact{
            text-align: center;
            margin-bottom: 10px;
            border-top: 1px #004662 solid;
        }
        body{
            padding: 0;
            margin: 0;
            height: 100vh;
            width: 100vw;
        }
        h1, h3,h4{
            font-family: "Baloo Thambi 2", monospace;
            color: #00FFFF;
        }
        h3{
            font-size: 20px;
            font-weight: 600;
        }
        h4{
            font-size: 19px;
        }
    </style>


</head>

<body data-root-url={{ asset('') }}>
<div id="app">
    <div class="layout">
       <div class="content">
           <div class='maintenance'>
               <div class='card'>
                   <section class='image'>
                       <img alt='' src='/assets/images/LONG_EMS_BC_2.png' />
                   </section>
                   <h1>maintenance en cours</h1>
                   <section class='contact'>
                       <h3>Plus d'information sur discord</h3>
                       <h4>salon #note-mdt</h4>
                   </section>
               </div>
           </div>
       </div>
    </div>
</div>

</body>
</html>
