<?php $total = 0 ?>
    <!DOCTYPE html>
<html lang="fr">
<title>Export facture du {{$infos['from']}} au {{$infos['to']}}</title>
<head>
    <style>

        .header{
            width: 100%;

        }
        body{
            display: flex;
            flex-direction: column;
        }
        .factures{

        }
        .factures td{
            border: #1B2037 1px solid;
            text-align: center;
            padding: 5px;
        }

        .factures th{
            background-color: #1B2037;
            color: #E9E9E9;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }
        h4{
            text-align: center;
            font-family: "Baloo Thambi 2", monospace;
        }
        .bottom{
            border: none !important;
        }

    </style>
</head>
<body>
<table class="header">
    <tr style="width: 100%">
        <td><img src="{{public_path('assets/images/LONG_EMS_BC_2.png')}}" alt="" style="width: 400px; margin-left: 150px"></td>
    </tr >
    <tr style="width: 100%">
        <td><h1 style="text-align: center">Impayées</h1></td>
    </tr>
    <tr style="width: 100%">
        <td><h4>Du {{$infos['from']}} au {{$infos['to']}} </h4></td>
    </tr>
</table>
<table class="factures">
    <thead>
    <tr id="head">
        <th>date</th>
        <th>patient</th>
        <th>montant</th>
    </tr>
    </thead>
    <tbody>
    @foreach($factures as $facture)
        <tr>
            <td>{{date('d/m/Y', strtotime($facture->created_at))}}</td>
            <td>{{$facture->GetPatient->name}}</td>
            <td>${{$facture->price}}</td>
        </tr>
    @endforeach
    <tr>
        <td class="bottom"></td>
        <td class="bottom"></td>
        <td style="background-color: #1B2037; color: #ED3444">${{$infos['total']}}</td>
    </tr>
    </tbody>
</table>
</body>
</html>

