<html lang="fr">
    <head>
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
                height: 130px;
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
            <img src="./assets/images/LONG_EMS_BC_2.png" alt="">
            <h1>Impayées</h1>
        </div>
        <h4>Du {{$data['infos']['from']}} au {{$data['infos']['from']}}</h4>
        <table>
            <thead>
                <tr>
                    <td>date</td>
                    <td>heure</td>
                    <td>patient</td>
                    <td>montant</td>
                </tr>
            </thead>
            <tbody>
            @foreach($data['impaye'] as $line)
                <tr>
                    <td>{{date('d/m/Y', strtotime($line->created_at))}}</td>
                    <td>{{date('H:i', strtotime($line->created_at))}}</td>
                    <td>{{$line->patient->vorname.' '.$line->patient->name}}</td>
                    <td>{{$line->price}}$</td>
                </tr>
            @endforeach
               </tbody>
        </table>
    </body>
</html>
