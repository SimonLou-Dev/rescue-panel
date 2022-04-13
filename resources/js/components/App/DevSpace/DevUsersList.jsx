import React, {useContext, useEffect, useState} from 'react';
import axios from 'axios';
import Searcher from "../../props/Searcher";
import UpdaterBtn from "../../props/UpdaterBtn";
import {useHistory} from "react-router-dom";
import UserContext from "../../context/UserContext";

function DevUsersList(props) {
    const history = useHistory();
    const user= useContext(UserContext)
    const [search, setSearch] = useState('');
    const [userList, setUserList] = useState([]);
    const [fireList, setFireList] = useState([]);
    const [medicList, setMedicList] = useState([]);


    const UserList = async (a = search ) => {

        if(a !== search){
            setSearch(a);
        }

        await axios({
            url : '/data/dev/users' +'?query='+a,
            method: 'GET'
        }).then(r => {
            setUserList(r.data.users)
            setFireList(r.data.fire)
            setMedicList(r.data.medic)
        })

    }

    const checkStaff = (user) => {
        return user.moderator && (user.medic_grade_id == 6 || user.fire_grade_id == 6)
    }

    const checkDev = (user) => {
        return user.dev && (user.medic_grade_id == 7 || user.fire_grade_id == 7)
    }

    const setStaff = async (user) => {
        await axios({
            method: 'PUT',
            url: '/data/dev/user/' + user.id + '/staff'
        }).then(()=>UserList())
    }

    const setGrade= async (user, service, gradeId) => {
        await axios({
            method: 'PUT',
            url: '/data/dev/user/' + user.id + '/grade/' + service + '/' + gradeId
        }).then(()=>UserList())

    }

    const setService= async (user, service) => {
        await axios({
            method: 'PUT',
            url: '/data/dev/user/' + user.id + '/service/' + service
        }).then(()=>UserList())
    }

    const setCrossService= async (user) => {
        await axios({
            method: 'PUT',
            url: '/data/dev/user/' + user.id + '/cross'
        }).then(()=>UserList())
    }

    const setDev= async (user) => {
        await axios({
            method: 'PUT',
            url: '/data/dev/user/' + user.id + '/dev'
        }).then(()=>UserList())
    }

    const remove = async (user) => {
        await axios({
            method: 'DELETE',
            url: '/data/dev/user/' + user.id
        }).then(()=>UserList())
    }


    useEffect(()=>{
        if(!user.dev || user.medic_grade_id !== 7 || user.fire_grade_id !== 7) history.push('/dashboad')
        UserList();
    }, [])

    return (<div className={'TablePage'}>
        <div className={'PageCenter'}>
            <div className={'table-header'}>
                <Searcher value={search} callback={(v) => {UserList(v)}}/>
                <UpdaterBtn callback={UserList}/>
            </div>
            <div className={'table-container'}>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>name</th>
                    <th>ninja</th>
                    <th>discord id</th>
                    <th>fire</th>
                    <th>medic</th>
                    <th>cross</th>
                    <th>staff</th>
                    <th>dev</th>
                    <th>bc</th>
                    <th>fire grade</th>
                    <th>medic grade</th>
                    <th>remove</th>
                </tr>
                </thead>
                <tbody>
                {userList && userList.map((u)=>
                    <tr key={u.id}>
                        <td>{u.id}</td>
                        <td>{u.name}</td>
                        <td><a href={'/dev/ninja/'+u.id}><img alt={''} src={'/assets/images/ninja.png'}/></a> </td>
                        <td>{u.discord_id}</td>
                        <td><button className={'btn'} onClick={()=>setService(u, 'LSCoFD')}><img alt={''} src={'/assets/images/'+(u.fire ? 'accept': 'decline')+'.png'}/></button> </td>
                        <td><button className={'btn'} onClick={()=>setService(u, 'SAMS')}><img alt={''} src={'/assets/images/'+(u.medic ? 'accept': 'decline')+'.png'}/></button> </td>
                        <td><button className={'btn'} onClick={()=>setCrossService(u)}><img alt={''} src={'/assets/images/'+(u.crossService ? 'accept': 'decline')+'.png'}/></button> </td>
                        <td><button className={'btn'} onClick={()=>setStaff(u)} ><img alt={''} src={'/assets/images/'+(checkStaff(u) ? 'accept': 'decline')+'.png'}/></button> </td>
                        <td><button className={'btn'} onClick={()=>setDev(u)}><img alt={''} src={'/assets/images/'+(checkDev(u) ? 'accept': 'decline')+'.png'}/></button> </td>
                        <td>{u.bc_id}</td>
                        <td>
                            <select value={u.fire_grade_id} onChange={(e)=>{setGrade(u,'fire', e.target.value)}}>
                                <option value={1}>sans grade</option>
                                {fireList && fireList.map((f)=>
                                    <option key={f.id + '_' + u.id} value={f.id}>{f.name}</option>
                                )}
                            </select>
                        </td>
                        <td>
                            <select value={u.medic_grade_id} onChange={(e)=>{setGrade(u,'SAMS', e.target.value)}}>
                                <option value={1}>sans grade</option>
                                {medicList && medicList.map((m)=>
                                    <option key={m.id + '_' + u.id} value={m.id}>{m.name}</option>
                                )}
                            </select>
                        </td>
                        <td>
                            <button className={'btn'} onClick={()=>remove(u)}><img alt={''} src={'/assets/images/'+ (u.deleted_at !== null ? 'accept' : 'decline') +'.png'}/></button>
                        </td>
                    </tr>
                )}
                </tbody>
            </table>

        </div>
        </div>

    </div>)
}

export default DevUsersList;
