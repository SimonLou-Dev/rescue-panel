import React, {useContext, useEffect, useState} from 'react';
import Searcher from '../../props/Searcher';
import PageNavigator from "../../props/PageNavigator";
import {Link} from "react-router-dom";
import axios from "axios";
import CardComponent from "../../props/CardComponent";
import UserContext from "../../context/UserContext";

function Vols(props) {
    const [popupDisplayed, displayPopup] = useState(false);
    const [search, setSearch] = useState("");
    const [vols, setVols]= useState([]);
    const [paginate, setPagination]= useState([]);
    const [page, setPage] = useState(1);

    const [lieux, setLieux] = useState(0);
    const [reason, setReason] = useState('');
    const [lieuxList, setLieuxList] = useState([]);

    const [errors, setErrors] = useState([]);

    const user = useContext(UserContext);

    const searchVols = async (searche = search, pagee = page) => {
        if (searche !== search) setSearch(search);
        if (pagee !== page) setPage(page);
        await axios({
            method: 'GET',
            url: '/data/vols?querry'+searche+'&page='+pagee,
        }).then((r) => {
            let final = [];
            let keys = Object.keys(r.data.vols.data);
            keys.forEach((key) => {
                final[key] = r.data.vols.data[key];
            });
            setVols(final)
            setPagination(r.data.vols)
            setLieuxList(r.data.places)
        })
    }

    useEffect(()=>{
        searchVols();
    }, []);


    const postVols = async () => {
        await axios({
            method: 'POST',
            url: '/data/vols',
            data:{
                reason,
                lieux,
            }
        }).then(r => {
            if(r.status === 201) {
                setReason('')
                setLieux(0)
                displayPopup(false);
                searchVols();
            }
        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })
    }

    return (<div className={'Factures'}>
        <div className={'FactureCenter ' + (popupDisplayed ? 'popupBg':'')}>
            <div className={'table-header'}>
                <PageNavigator prev={()=> {searchVols(search,page-1)}} next={()=> {searchVols(search,page+1)}} prevDisabled={(paginate.prev_page_url === null)} nextDisabled={(paginate.next_page_url === null)}/>
                <Searcher value={search} callback={(v) => {searchVols(v)}}/>
                <button className={'btn'} disabled={!(user.grade.admin || user.pilote)} onClick={()=>{displayPopup(true)}}>ajouter</button>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>date</th>
                            <th>Lieux</th>
                            <th>raison</th>
                            <th>personnel</th>
                        </tr>
                    </thead>
                    <tbody>
                    {vols && vols.map((item) =>
                        <tr key={item.id}>
                            <td>{item.id}</td>
                            <td>{item.decollage}</td>
                            <td>{item.get_lieux.name}</td>
                            <td>{item.raison}</td>
                            <td className={'clickable'}><Link to={'/personnel/fiche/' + item.get_user.id}>{item.get_user.name + (user.dev ? '('+item.service  +')' : '')}</Link></td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>
        {popupDisplayed &&
            <section className={'popup'}>
                <CardComponent  title={'ajouter un vol'}>
                    <div className={'form-group form-column'}>
                        <label>raison</label>
                        <input type={'text'} className={'form-input'} list={'autocomplete'} value={reason} onChange={(e)=>{setReason(e.target.value)}}/>
                        {errors.reason &&
                            <div className={'errors-list'}>
                                <ul>
                                    {errors.reason.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-group form-column'}>
                        <label>lieux</label>
                        <select className={'form-input'} value={lieux} onChange={(e)=>{setLieux(e.target.value)}}>
                            <option value={0} disabled={true}>choisir</option>
                            {lieuxList && lieuxList.map((u) =>
                                <option value={u.id} key={u.id}>{u.name}</option>
                            )}
                        </select>
                        {errors.lieux &&
                            <div className={'errors-list'}>
                                <ul>
                                    {errors.lieux.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-group form-line'}>
                        <button className={'btn'} onClick={()=>{
                            setReason('');
                            setLieux(0)
                        }}>effacer</button>
                        <button className={'btn'} onClick={postVols}>envoyer</button>
                        <button className={'btn'} onClick={()=>{displayPopup(false)}}>fermer</button>
                    </div>
                </CardComponent>
            </section>
        }

    </div> )
}

export default Vols;
