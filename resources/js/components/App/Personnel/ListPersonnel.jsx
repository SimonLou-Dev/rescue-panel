import React, {useContext, useEffect, useState} from 'react';
import PageNavigator from "../../props/PageNavigator";
import Searcher from "../../props/Searcher";
import axios from "axios";
import SwitchBtn from "../../props/SwitchBtn";
import UserContext from "../../context/UserContext";

function ListPersonnel(props) {

    const [paginate, setPagination]= useState([]);
    const [page, setPage] = useState(1);
    const [search, setSearch] = useState("");
    const [users, setUsers]= useState([]);
    const [gradeList, setGradesList] = useState([]);
    const gle = useContext(UserContext)


    useEffect(()=>{
        UserList();
    }, [])

    const UserList = async (a = search , c = page) => {
        if(c !== page){
            setPage(c);
        }
        if(a !== search){
            setSearch(a);
            c = 1;
            setPage(1);
        }
        await axios({
            url : '/data/users/getall' +'?query='+a+'&page='+c,
            method: 'GET'
        }).then(r => {
            let final = [];
            let keys = Object.keys(r.data.users.data);
            keys.forEach((key) => {
                final[key] = r.data.users.data[key];
            });
            setUsers(final);
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
                <PageNavigator prev={()=> {UserList(search,page-1)}} next={()=> {UserList(search,page+1)}} prevDisabled={(paginate.prev_page_url === null)} nextDisabled={(paginate.next_page_url === null)}/>
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
                            <td><select value={u.grade.id} disabled={!(gle.grade.admin || gle.grade.set_grade)} onChange={async (e)=>{
                                await axios({
                                    method: 'POST',
                                    url: '/data/users/setgrade/'+ e.target.value +'/'+u.id
                                }).then(r=>{UserList()})
                            }}>
                                <option value={1} disabled={true}>sans grade</option>
                                {gradeList && gradeList.map((g)=>
                                    <option key={g.id+'.'+u.id} value={g.id} disabled={(g.name === 'default')}>{g.name} {gle.dev === true && ' ' + g.service}</option>
                                )}
                            </select></td>
                            <td>
                                <SwitchBtn checked={u.pilote} disabled={!(gle.grade.admin || gle.grade.set_pilote)} number={'A'+u.id} callback={async () => {
                                    await axios({
                                        method: 'PUT',
                                        url: '/data/users/pilote/' + u.id
                                    }).then(r=>{UserList()})
                                }}/>
                            </td>
                            <td>
                                <SwitchBtn checked={u.crossService} disabled={!(gle.grade.admin || gle.grade.set_crossService)} number={'B'+u.id} callback={async () => {
                                    await axios({
                                        method: 'PUT',
                                        url: '/data/users/setCrossService/' + u.id
                                    }).then(r=>{UserList()})
                                }}/>
                            </td>
                            <td>{u.service}</td>
                            <td><button className={'btn'} disabled={!(gle.grade.admin || gle.grade.set_other_service)}  onClick={async () => {
                                await axios({
                                    method: 'PUT',
                                    url: '/data/service/setbyadmin/' + u.id
                                }).then(r=>{UserList()})
                            }}>
                                <img alt={''} src={'/assets/images/' + (u.OnService ? 'accept' : 'decline') +'.png'}/></button></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>

    </div> )
}

export default ListPersonnel;
