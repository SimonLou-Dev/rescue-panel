<!DOCTYPE html>
<html lang="FR">
<head>
    <title>rapport_<?php echo $test->id ?>.pdf</title>
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
            margin-top: 120px;
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
        .fixedd{
            text-decoration: black underline;
            margin-left: 200px;
            font-weight: 600;
        }
        .fixed{
            text-decoration: black underline;
            margin-left: 10px;
            font-weight: 600;
        }
        .item:last-child{
            border-bottom: none;
        }
        .signature{
            display: flex;
            flex-direction: column;
            height: 200px;
        }
        .contents{
            margin-top: 110px;
            margin-left: 120px;
        }
        .text,img{
            margin-left: 100px;
            margin-top: 25px;
        }
        .signiature{
            font-weight: 700;
            font-family: 'Brush Script MT', monospace;
            font-size: 40px;
            margin-top: 20px;
            margin-left: 50px;
        }
    </style>
</head>
<body>
<div class="head">
    <?php echo '<img src="'. public_path('assets/images/LONG_EMS_BC_2.png') .'" alt="">' ?>
    <h1>Test de poudre #<?php echo $test->id ?></h1>
</div>
<div class="card">
    <div class="card-head">
        <h3>Identification du patient</h3>
    </div>
    <div class="card-content id">
        <div class="item">
            <p><span class="fixed">prénom nom :</span><?php echo ' '.$test->GetPatient->name . ' ' . $test->GetPatient->vorname ?></p>
        </div>
        <div class="item">
            <p><span class="fixed">n° de téléphone :</span><?php echo ' '.$test->GetPatient->tel ?></p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-head">
        <h3>Section administrative : </h3>
    </div>
    <div class="card-content sa">
        <div class="item">
            <p><span class="fixed">Date et heure du prélèvement :</span><?php echo ' '. date('d/m/y H:i', strtotime($test->started_at)) ?> </p>
        </div>
        <div class="item">
            <p><span class="fixed">Lieux de prélèvement :</span><?php echo ' '. $test->lieux_prelevement ?></p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-head">
        <h3>Résultat</h3>
    </div>
    <div class="item">
        <p><span class="fixed">Presence sur les vêtements :</span><?php echo ' '. ($test->on_clothes_positivity ? 'Positif': 'Negatif') ?> </p>
    </div>
    <div class="item">
        <p><span class="fixed">Presence sur la peau :</span><?php echo ' '. ($test->on_skin_positivity ? 'Positif': 'Negatif') ?> </p>
    </div>
</div>

<div class="card">
    <div class="card-head">
        <h3>Personnel traitant : </h3>
    </div>
    <div class="card-content pi">
        <div class="item">
            <p><span class="fixed">prénom nom :</span> <?php echo $user  ?></p>
        </div>
        <div class="item signature">
            <p><span class="fixed">Signature :</span></p>
            <div class="contents">
                <p class="signiature"> <?php echo $user  ?></p>
                <div class="text"><?php echo '<img class="imgsignature" width="100px" alt=""src="'. public_path('assets/images/signature.png') .'" alt="">' ?></div>


            </div>
        </div>
    </div>
</div>

</body>
</html>
