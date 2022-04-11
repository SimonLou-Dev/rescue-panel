<!DOCTYPE html>
<html lang="FR">
<head>
    <title>rapport_incendie_{{$report->id}}.pdf</title>
    <style>
        body{
            display: flex;
            flex-direction: column;
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
            height: 100px;
        }

    </style>
</head>
<body>
<table class="bordered" style="table-layout: fixed; width: 100%; margin-bottom: 15px">
    <tr class="font-12" style="width: 90%">
        <td style="width: 60%;"><h1 style=";font-weight: 700;font-family: 'Baloo Thambi 2', monospace;">Rapport d'incendie</h1></td>
        <td style="width: 20%;"><img src="{{public_path('assets/images/LSCoFD.png')}}" alt="" style="width: 150px; height: 150px; margin-left: 0px"></td>
    </tr>
</table>
<div class="card">
    <div class="card-head">
        <h3>Identification du feux</h3>
    </div>
    <div class="card-content id">
        <div class="item">
            <p><span class="fixed">Propiété :</span> {{$report->property_number}}</p>
        </div>
        <div class="item">
            <p><span class="fixed">Conté :</span> {{$report->compte}}</p>
        </div>
        <div class="item">
            <p><span class="fixed">Type d'incendie :</span>{{$report->GetType->name}}</p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-head">
        <h3>Section administrative : </h3>
    </div>
    <div class="card-content sa">
        <div class="item">
            <p><span class="fixed">Date et heure de l'intervention :</span>{{$report->GetBC->created_at}} </p>
        </div>
        <div class="item">
            <p><span class="fixed">Id Black Code :</span>{{$report->GetBC->id}}</p>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-head">
        <h3>Description</h3>
    </div>
    <div class="card-content bl">
        <p>{{$report->GetBC->description}}</p>
    </div>
</div>
<div class="card">
    <div class="card-head">
        <h3>Intervenants : </h3>
    </div>
    <div class="card-content ATA">
        <p>
            @foreach($report->GetBC->GetPersonnel as $personnel)
                {{' ' . $personnel->name}}
            @endforeach
        </p>
    </div>
</div>


<div class="card">
    <div class="card-head">
        <h3>Personnel traitant : </h3>
    </div>
    <div class="card-content pi">
        <div class="item">
            <p><span class="fixed">Intervention enregistrée le :</span> {{$report->created_at}}</p>
        </div>
        <div class="item">
            <p><span class="fixed">Lancée par :</span> {{$report->GetBC->GetUser->name}}</p>
        </div>

        <div class="item signature">
            <p><span class="fixed">Signature :</span></p>
            <div class="contents">
                <p style="font-weight: 700;font-family: 'Brush Script MT', monospace;font-size: 25px;margin-top: 20px; margin-left: 50%"> {{$report->GetBC->GetUser->name}} </p>
            </div>
        </div>
    </div>
</div>

</body>
</html>

