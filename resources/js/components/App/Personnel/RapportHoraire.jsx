import React, {useEffect, useState} from 'react';
import PageNavigator from "../../props/PageNavigator";
import Searcher from "../../props/Searcher";
import axios from "axios";
import UpdaterBtn from "../../props/UpdaterBtn";
import Button from "../../props/Button";
import CardComponent from "../../props/CardComponent";


function RapportHoraire(props) {
    const [paginate, setPagination]= useState([]);
    const [page, setPage] = useState(1);
    const [search, setSearch] = useState("");
    const [users, setUsers]= useState([]);
    const [maxWeek, setMaxWeek] = useState(undefined);
    const [currentWeek, setCurrentWeek] = useState(undefined);
    const [popup, setPoup] = useState(false)
    const [modifiedId,setModifidedId] = useState(null)
    const [modifiedName, setModifiedname] = useState('')
    const [action, setAction] = useState(0)
    const [time, setTime] = useState('')
    const [popupErrors, setPopupErrors] = useState([])

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

    const openPopup = (userId = null, userName = null) => {
        if(popup){
            setPoup(false)
            setModifiedname('')
            setModifidedId(null)
            setAction(0)
            setTime('')
        }else{
            setPoup(true)
            setModifiedname(userName)
            setModifidedId(userId)
        }
    }

    const sendModif = async () => {
        await axios({
            method: 'PUT',
            url: '/data/service/admin/modify/'+modifiedId,
            data: {
                action: (action - 1),
                time,
            }
        }).then(r => {
            openPopup()
            UserList()
        }).catch(error => {
            if (error.response.status === 422) {
                setPopupErrors(error.response.data.errors)
            }
        })

    }

    return (<div className={'TablePage'}>
        <div className={'PageCenter ' + (popup ? 'popupBg':'')}>
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
                            <td >{u.samedi}</td>
                            <td className={'clickable'} onClick={()=>{openPopup(u.get_user.id, u.get_user.name)}}>{u.ajustement}</td>
                            <td>{u.total}</td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>
        {popup &&
            <section className={'popup'}>
                <CardComponent  title={'Modification du temps de service de ' + modifiedName}>
                    <div className={'form-group form-column'}>
                        <label>action</label>
                        <select value={action} onChange={(e)=>{setAction(e.target.value)}}>
                            <option value={0} disabled={true}>choisir</option>
                            <option value={1}>enlever</option>
                            <option value={2}>ajouter</option>
                        </select>
                        {popupErrors.action &&
                            <div className={'errors-list'}>
                                <ul>
                                    {popupErrors.action.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Temps (23h59 max)</label>
                        <input type={'time'} className={'form-input'} value={time} onChange={(e)=>{setTime(e.target.value)}}/>
                        {popupErrors.time &&
                            <div className={'errors-list'}>
                                <ul>
                                    {popupErrors.time.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-group form-line'}>
                        <button className={'btn'} onClick={()=>{
                            setTime('');
                            setAction(0);
                        }}>effacer</button>
                        <button className={'btn'} onClick={sendModif}>envoyer</button>
                        <button className={'btn'} onClick={()=>{openPopup()}}>fermer</button>
                    </div>
                </CardComponent>
            </section>
        }

    </div>  )
}

export default RapportHoraire;
