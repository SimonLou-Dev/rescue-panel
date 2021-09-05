<?php $total = 0 ?>
<!DOCTYPE html>
<html lang="fr">
<title>Facture PDF</title>
<head>
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
            height: 150px;
        }
        .head img{
            margin: 0 15%;
            width: 70%;
        }
        .head h1{
            text-align: center;
            margin-top: 10px;
            font-weight: 700;
            font-family: "Baloo Thambi 2", monospace;
            margin-bottom: 0;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }
        td{
            border: #0C2646 1px solid;
            text-align: center;
            padding: 5px;
        }
        thead th{
            background-color: #0C2646;
            color: #00FFFF;
        }
        h4{
            text-align: center;
            font-family: "Baloo Thambi 2", monospace;
        }
        .bottom{
            border-bottom: none;
            border-left: none;
            border-right: none;
        }

    </style>
</head>
<body>
<div class="head">
    <img src="LONG_EMS_BC_2.png" alt="">
    <h1>Impayées</h1>
</div>
<h4>Du <?php echo $infos['from'] ?> au <?php echo $infos['to'] ?> </h4>
<table>
    <thead>
    <tr id="head">
        <th>date</th>
        <th>heure</th>
        <th>patient</th>
        <th>montant</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data['impaye'] as $line) {
        $first = "<td>";
        $end = "</td>";
        echo "<tr>";
        echo $first . date('d/m/Y', strtotime($line->created_at)) . $end;
        echo $first . date('H:i', strtotime($line->created_at)) . $end;
        echo $first . ($line->GetPatient->vorname.' '.$line->GetPatient->name) . $end;
        echo $first . '$' . $line->price . $end;
        $total += $line->price;
        echo '</tr>';
        }
    ?>
    <tr>
        <td class="bottom"></td>
        <td class="bottom"></td>
        <td class="bottom"></td>
        <td>$<?php echo $total?></td>
    </tr>
    </tbody>
</table>
</body>
</html>

