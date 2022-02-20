import React, {useEffect, useState} from 'react';
import PageNavigator from "../../props/PageNavigator";
import Searcher from "../../props/Searcher";
import axios from "axios";
import SwitchBtn from "../../props/SwitchBtn";

function ListPersonnel(props) {

    const [paginate, setPagination]= useState([]);
    const [page, setPage] = useState(1);
    const [search, setSearch] = useState("");
    const [users, setUsers]= useState([]);
    const [gradeList, setGradesList] = useState([]);


    useEffect(()=>{
        UserList();
    }, [])

    const UserList = async (a = search , c = page) => {
        if(c !== page){
            setPage(c);
        }
        if(a !== search){
            setSearch(a);
        }
        await axios({
            url : '/data/users/getall' +'?query='+a+'&page='+c,
            method: 'GET'
        }).then(r => {
            setUsers(r.data.users.data);
            setPagination(r.data.users);
            setGradesList(r.data.serviceGrade);

        })

    }

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'TablePage'}>
        <div className={'PageCenter'}>
            <div className={'table-header'}>
                <PageNavigator prev={()=> {UserList(search,page-1)}} next={()=> {UserList(search,page-1)}} prevDisabled={(paginate.prev_page_url === null)} nextDisabled={(paginate.next_page_url === null)}/>
                <Searcher value={search} callback={(v) => {UserList(v)}}/>
                <a href={'/data/users/export'} target={'_blank'} className={'btn exporter'}><img alt={''} src={'/assets/images/xls.png'}/></a>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>nom</th>
                        <th>matricule</th>
                        <th>tel</th>
                        <th>discord id</th>
                        <th>grade</th>
                        <th>pilote</th>
                        <th>crossService</th>
                        <th>service actuel</th>
                        <th>service</th>
                    </tr>
                    </thead>
                    <tbody>
                    {users && users.map((u)=>
                        <tr key={u.id}>
                            <td>{u.id}</td>
                            <td onClick={()=>{Redirection('/personnel/fiche/'+u.id)}} className={'link'}>{u.name}</td>
                            <td>{u.matricule}</td>
                            <td>{u.tel}</td>
                            <td>{u.discord_id}</td>
                            <td><select value={u.grade.id}>
                                {gradeList && gradeList.map((g)=>
                                    <option key={g.id+'.'+u.id} value={g.id}>{g.name}</option>
                                )}
                            </select></td>
                            <td>
                                <SwitchBtn checked={u.pilote} number={'A'+u.id} callback={async () => {
                                    await axios({
                                        method: 'PUT',
                                        url: '/data/users/pilote/' + u.id
                                    }).then(r=>{UserList()})
                                }}/>
                            </td>
                            <td>
                                <SwitchBtn checked={u.crossService} number={'A'+u.id} callback={async () => {
                                    await axios({
                                        method: 'PUT',
                                        url: '/data/users/pilote/' + u.id
                                    }).then(r=>{UserList()})
                                }}/>
                            </td>
                            <td>{u.service}</td>
                            <td><button className={'btn'}><img alt={''} src={'/assets/images/' + (u.OnService ? 'accept' : 'decline') +'.png'}
                            onClick={async () => {
                                await axios({
                                    method: 'PUT',
                                    url: '/data/service/setbyadmin/' + u.id
                                }).then(r=>{UserList()})
                            }}
                            /></button></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>

    </div> )
}

export default ListPersonnel;
