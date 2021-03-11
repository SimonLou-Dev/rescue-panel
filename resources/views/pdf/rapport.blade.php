<html lang="FR">
    <head>
        <title>rapport_{{$rapport->id}}.pdf</title>
        <style>
            body{
                width: 100vw;
                height: 100vh;
                display: flex;
                flex-direction: column;
            }
            .head{
                display: flex;
                flex-direction: column;
                width: 90%;
                margin: 0 auto;
                height: 150px;
            }
            .head img{
                margin: 0 15%;
                width: 70%;
            }
            .head h1{
                text-align: center;
                margin-top: 90px;
                font-weight: 700;
                font-family: "Baloo Thambi 2", monospace;
                margin-bottom: 0;
            }
            .card{
                border: #0C2646 2px solid;
                margin-bottom: 20px;
                padding: 0;
                border-radius: 7px;
            }
            .card-head{
                width: 100%;
                border-bottom: #0C2646 dashed 2px;
                margin: 0;
            }
            .card-head h3{
                margin: 5px 5px 5px 15px;
                font-size: 25px;
            }
            .card-content{
                margin: 0;
                padding: 0;
            }
            .card-content p {
                margin: 5px 5px 5px 10px;
                text-indent: 15px;
            }
            .item{
                border-bottom: #0C2646 1px dashed;
            }
            .item p{
                font-size: 18px;
                line-height: 19px;
                margin: 5px 5px 5px 10px;
            }
            .fixed{
                text-decoration: black underline;
                font-weight: 600;
            }
            .item:last-child{
                border-bottom: none;
            }
            .imgsignature{
                margin-left: 80%;
            }
        </style>
    </head>
    <body>
        <div class="head">
            <img src="./assets/images/LONG_EMS_BC_2.png" alt="">
            <h1>Rapport d'intervention</h1>
        </div>
        <div class="card">
            <div class="card-head">
                <h3>Identification du patient</h3>
            </div>
            <div class="card-content id">
                <div class="item">
                    <p><span class="fixed">Nom, prénom :</span>{{' '.$rapport->Patient->name . ' ' . $rapport->Patient->vorname}}</p>
                </div>
                <div class="item">
                    <p><span class="fixed">n° de téléphone :</span>{{' '.$rapport->Patient->tel}}</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head">
                <h3>Section administrative : </h3>
            </div>
            <div class="card-content sa">
                <div class="item">
                    <p><span class="fixed">Date et heure de l'intervention :</span>{{' '. date('d/m/y H:i', strtotime($rapport->created_at))}}</p>
                </div>
                <div class="item">
                    <p><span class="fixed">Transport :</span>{{' '. $rapport->Hospital->name}}</p>
                </div>
                <div class="item">
                    <p><span class="fixed">Facturation :</span>{{' '. $rapport->prix.'$'}} {{$rapport->facture->payed?'payé':'impoayé'}}</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head">
                <h3>Blessure constatée</h3>
            </div>
            <div class="card-content bl">
                <p>{{$rapport->description}}</p>
            </div>
        </div>
        <div class="card">
            <div class="card-head">
                <h3>Arret total d'activité : </h3>
            </div>
            <div class="card-content ATA">
                <p><span class="fixed">Duréee :</span> du {{date('d/m/y à H:i', strtotime($rapport->ATA_start))}} au {{date('d/m/y à H:i', strtotime($rapport->ATA_end))}}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h3>Personnel traitant : </h3>
            </div>
            <div class="card-content pi">
                <div class="item">
                    <p><span class="fixed">Date et heure du rapport :</span> {{date('d/m/y à H:i', strtotime($rapport->created_at))}}steaq</p>
                </div>
                <div class="item signature">
                    <p><span class="fixed">Signature :</span></p>
                    <img class="imgsignature" src='./assets/images/signature.png' width="100px" alt="">
                </div>
            </div>
        </div>

    </body>
</html>
