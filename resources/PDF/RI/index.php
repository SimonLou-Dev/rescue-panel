<!DOCTYPE html>
<html lang="FR">
<head>
    <title>rapport_<?php echo $rapport->id ?>.pdf</title>
    <style>
        body{
            display: flex;
            flex-direction: column;
        }
        .head{
            display: flex;
            flex-direction: column;
            width: 90%;
            margin: 0 auto;
            height: 220px;
        }
        .head img{
            margin: 0 15%;
            width: 70%;
        }
        .head h1{
            text-align: center;
            margin-top: 20px;
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
            font-size: 15px;
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
        signature{
            display: flex;
            flex-direction: column;
        }
        .contents{
            display: flex;
            flex-direction: row;
            justify-content: space-evenly;
        }
        .signiature{
            font-weight: 700;
            font-family: 'Brush Script MT', monospace;
            font-size: 40px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="head">
    <img src="LONG_EMS_BC_2.png" alt="">
    <h1>Rapport d'intervention</h1>
</div>
<div class="card">
    <div class="card-head">
        <h3>Identification du patient</h3>
    </div>
    <div class="card-content id">
        <div class="item">
            <p><span class="fixed">prénom nom :</span><?php echo ' '.$rapport->GetPatient->name . ' ' . $rapport->GetPatient->vorname ?></p>
        </div>
        <div class="item">
            <p><span class="fixed">n° de téléphone :</span><?php echo ' '.$rapport->GetPatient->tel ?></p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-head">
        <h3>Section administrative : </h3>
    </div>
    <div class="card-content sa">
        <div class="item">
            <p><span class="fixed">Date et heure de l'intervention :</span><?php echo ' '. date('d/m/y H:i', strtotime($rapport->started_at)) ?> </p>
        </div>
        <div class="item">
            <p><span class="fixed">Transport :</span><?php echo ' '. $rapport->GetTransport->name ?></p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-head">
        <h3>Blessure constatée</h3>
    </div>
    <div class="card-content bl">
        <p><?php echo $rapport->description ?></p>
    </div>
</div>
<?php if($rapport->ATA_start != $rapport->ATA_end):?>
    <div class="card">
        <div class="card-head">
            <h3>Arret total d'activité : </h3>
        </div>
        <div class="card-content ATA">
            <p><span class="fixed">Du :</span> <?php echo date('d/m/y à H:i', strtotime($rapport->ATA_start)) ?> </p>
            <p><span class="fixed">Au :</span> <?php echo date('d/m/y à H:i', strtotime($rapport->ATA_end)) ?></p>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-head">
        <h3>Personnel traitant : </h3>
    </div>
    <div class="card-content pi">
        <div class="item">
            <p><span class="fixed">Intervention enregistrée le :</span> <?php echo date('d/m/y à H:i', strtotime($rapport->created_at)) ?></p>
        </div>
        <div class="item">
            <p><span class="fixed">prénom nom :</span> <?php echo $user  ?></p>
        </div>
        <div class="item signature">
            <p><span class="fixed">Signature :</span></p>
            <div class="contents">
                <p class="signiature"> <?php echo $user  ?></p>
                <img class="imgsignature" src='signature.png' width="100px" alt="">
            </div>
        </div>
    </div>
</div>

</body>
</html>

