 @extends('dev.utils.layout')

 @section('content')

     <div class="dashboard">
         <section class="users">
             <div class="userService">
                 <div class="title">
                     <h4>Au SAMS: x</h4>
                 </div>
                 <div class="list">
                     @foreach($medicService as $user)
                         <div class="tag">
                             {{$user->name}}
                         </div>
                     @endforeach
                 </div>
             </div>
             <div class="userService">
                 <div class="title">
                     <h4>Au LSCoFD: x</h4>
                 </div>
                 <div class="list">
                     @foreach($fireService as $user)
                         <div class="tag">
                             {{$user->name}}
                         </div>
                     @endforeach
                 </div>
             </div>
         </section>
         <section class="logs">
            <div class="logs-list">
                <table>
                    <tr>
                        <th>fichier</th>
                        <th>taille</th>
                        <th>consulter</th>
                    </tr>
                    @foreach($files as $file)
                        <tr>
                            <td>{{$file['name']}}</td>
                            <td>{{$file['size']}}</td>
                            <td><a href="{{'/dev/data/logs/'.$file['name']}}"><img src="{{asset('assets/images/documents.png')}}" alt=""></a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
         </section>
     </div>

 @endsection
