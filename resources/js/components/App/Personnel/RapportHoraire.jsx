import React, {useEffect, useState} from 'react';
import PageNavigator from "../../props/PageNavigator";
import Searcher from "../../props/Searcher";
import axios from "axios";
import UpdaterBtn from "../../props/UpdaterBtn";


function RapportHoraire(props) {
    const [paginate, setPagination]= useState([]);
    const [page, setPage] = useState(1);
    const [search, setSearch] = useState("");
    const [users, setUsers]= useState([]);
    const [maxWeek, setMaxWeek] = useState(undefined);
    const [currentWeek, setCurrentWeek] = useState(undefined);

    const Redirection = (url) => {
        props.history.push(url)
    }

    useEffect(()=>{
        UserList(search, page, true)
    }, [])

    const UserList = async (a = search , c = page, loading = false) => {
        if(c !== page){
            setPage(c);
        }
        if(a !== search){
            setSearch(a);
            c = 1;
            setPage(1);
        }
        await axios({
            url : '/data/service/alluser/'+ (currentWeek ?? '')  +'?query='+a+'&page='+c,
            method: 'GET'
        }).then(r => {
            let final = [];
            let keys = Object.keys(r.data.service.data);
            keys.forEach((key) => {
                final[key] = r.data.service.data[key];
            });
            setUsers(final);
            setPagination(r.data.service);
            setMaxWeek(r.data.maxweek);
            if(loading){
             setCurrentWeek(r.data.maxweek);
            }
        })

    }

    return (<div className={'TablePage'}>
        <div className={'PageCenter'}>
            <div className={'table-header'}>
                <PageNavigator prev={()=> {UserList(search,page-1)}} next={()=> {UserList(search,page+1)}} prevDisabled={(paginate.prev_page_url === null)} nextDisabled={(paginate.next_page_url === null)}/>
                <Searcher value={search} callback={(v) => {UserList(v)}}/>
                <UpdaterBtn callback={UserList}/>
                <a href={'/data/service/admin/exel/'+currentWeek} target={'_blank'} className={'btn exporter'}><img alt={''} src={'/assets/images/xls.png'}/></a>
                <div className={'selector'}>
                    <input type={'number'} placeholder={'semaine n°'} max={maxWeek} value={currentWeek} onChange={(e)=>{setCurrentWeek(e.target.value)}}/>
                    <button onClick={()=>{UserList()}}><img alt={''} src={'/assets/images/search.png'}/></button>
                </div>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                    <tr>
                        <th>nom</th>
                        <th>remboursement</th>
                        <th>primes</th>
                        <th>dimanche</th>
                        <th>lundi</th>
                        <th>mardi</th>
                        <th>mercredi</th>
                        <th>jeudi</th>
                        <th>vendredi</th>
                        <th>samedi</th>
                        <th>modif</th>
                        <th>total</th>
                    </tr>
                    </thead>
                    <tbody>
                    {users && users.map((u)=>
                        <tr key={u.id}>
                            <td onClick={()=>{Redirection('/personnel/fiche/'+u.get_user.id)}} className={'clickable'}>{u.get_user.name}</td>
                            <td>${u.remboursement}</td>
                            <td>${u.prime}</td>
                            <td>{u.dimanche}</td>
                            <td>{u.lundi}</td>
                            <td>{u.mardi}</td>
                            <td>{u.mercredi}</td>
                            <td>{u.jeudi}</td>
                            <td>{u.vendredi}</td>
                            <td>{u.samedi}</td>
                            <td>{u.dimanche}</td>
                            <td>{u.total}</td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>

    </div>  )
}

export default RapportHoraire;
