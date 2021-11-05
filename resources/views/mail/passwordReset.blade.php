<!DOCTYPE>
<html lang="{{str_replace('_','-', app()->getLocale())}}">
<head>


</head>
<body data-root-url={{ asset('') }}>
<style>
    .mail{
        width: 100%;
        display: flex;
    }
    .center{
        display: flex;
        margin: 0 auto;
        flex-direction: column;
        justify-content: space-around;
        background-color: #0C2646;
        border-radius: 20px;
        width: 80%;
        max-width: 500px;
    }
    h1, h2{
        text-align: center;
        font-family: "Baloo Thambi 2", serif;
        font-weight: 800;
        color: #00FFFF;
    }

    img{
        margin-top: 10px auto 0 auto;
        max-width: 400px;
        width: 80%;
    }
    .separator{
        width: 100%;
        height: 1px;
        background-color: #0f2f57;
    }
    .btn {
        height: fit-content;
        margin: 20px auto;
        padding: 10px 20px;
        background-color: #004662;
        color: #00FFFF;
        border-radius: 20px;
        width: 125px;
        font-family: "Baloo Thambi 2", monospace;
        font-weight: 600;
        font-size: 25px;
        border: none;
        cursor: pointer;
        transition: 400ms cubic-bezier(0.28, -0.46, 0.33, 1.5);
        text-align: center;
    }
    .btn:hover{
         -webkit-transform: scale(1.2);
    }


</style>
<div id="mail">
    <div class="center">
        <h1>Blaine County Fire Department</h1>
        <div class="separator"></div>
        <h2>Réinitialisation de mot de passe</h2>
        <a class="btn" href="{{$token}}">Cliquez  ici</a>
    </div>
</div>
</body>
</html>
