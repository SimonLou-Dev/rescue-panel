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
        thead td{
            background-color: #0C2646;
            color: #00FFFF;
        }
        h4{
            text-align: center;
            font-family: "Baloo Thambi 2", monospace;
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
    <th>
        <td>date</td>
        <td>heure</td>
        <td>patient</td>
        <td>montant</td>
    </th>
    </thead>
    <tbody>
    <?php foreach ($data['impaye'] as $line) {
        echo '<tr>';
        echo '<td>' . date('d/m/Y', strtotime($line->created_at)) . '</td>';
        echo '<td>' . date('H:i', strtotime($line->created_at)) . '</td>';
        echo '<td>' . ($line->GetPatient->vorname.' '.$line->GetPatient->name) . '</td>';
        echo '<td> $' . $line->price . '</td>';
        $total += $line->price;
        echo '</tr>';
        }
    ?>
    <tr>
        <td style="border-bottom: none; border-left: none;"></td>
        <td style="border-bottom: none; border-left: none;"></td>
        <td style="border-bottom: none; border-left: none;"></td>
        <td>$<?php echo $total?></td>
    </tr>
    </tbody>
</table>
</body>
</html>

