@extends('dev.utils.layout')

@section('content')

    <div class="users">
        <div class="userList">
            <table>
                <tr>
                    <th>id</th>
                    <th>nom</th>
                    <th>ninja</th>
                    <th>matricule</th>
                    <th>discord_id</th>
                    <th>f</th>
                    <th>m</th>
                    <th>staff</th>
                    <th>dev</th>
                    <th>bc_id</th>
                    <th>medic grade</th>
                    <th>fire grade</th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td><button>ninja</button></td>
                        <td>{{$user->matricule}}</td>
                        <td>{{$user->discord_id}}</td>
                        <td><input type="checkbox"  {{$user->fire ? 'checked': ''}}></td>
                        <td> <input type="checkbox" {{$user->medic ? 'checked': ''}}></td>
                        <td><input type="checkbox"  {{$user->medic_grade_id===6 ? 'checked': ''}}></td>
                        <td><input type="checkbox"  {{$user->dev ? 'checked': ''}}></td>
                        <td>{{$user->bc_id}}</td>
                        <td><select>
                                <option value="1" {{$user->medic_grade_id === 1 ?'selected="selected"':''}}>sans grade</option>
                                @foreach($medicGrade as $grade)
                                    <option value="{{$grade->id}}" {{$user->medic_grade_id === $grade->id ?'selected="selected"':''}}>{{$grade->name}}</option>
                                @endforeach
                        </select></td>
                        <td><select>
                                <option value="1" {{$user->fire_grade_id === 1 ?'selected="selected"':''}}>sans grade</option>
                                @foreach($medicGrade as $grade)
                                    <option value="{{$grade->id}}" {{$user->fire_grade_id === $grade->id ?'selected="selected"':''}}>{{$grade->name}}</option>
                                @endforeach
                            </select></td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection
