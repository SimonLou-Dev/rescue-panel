import React, {useContext, useEffect, useState} from 'react';
import Searcher from "../../../props/Searcher";
import PageNavigator from "../../../props/PageNavigator";
import {Link} from "react-router-dom";
import axios from "axios";
import CardComponent from "../../../props/CardComponent";
import SwitchBtn from "../../../props/SwitchBtn";
import UserContext from "../../../context/UserContext";

function FactureList(props) {
    const [popupDisplayed, displayPopup] = useState(false);
    const [search, setSearch] = useState("");
    const [factures, setFactures]= useState([]);
    const [paginate, setPagination]= useState([]);
    const [page, setPage] = useState(1);

    const [payed, setPayed] = useState(false);
    const [montant, setMontant] = useState('');
    const [name, setName] = useState("");
    const [patientDataList, setPatientDataList] = useState([]);
    const [errors, setErrors] = useState([]);

    const [from, setFrom] = useState("");
    const [to, setTo] = useState("");

    const user = useContext(UserContext);

    const searchPatient = async (search) => {
        setName(search)
        if(search.length > 0){
            await axios({
                method: 'GET',
                url: '/data/patient/search/'+search,
            }).then((response)=>{
                setPatientDataList(response.data.patients);
            })
        }
    }

    useEffect(()=>{
        patientList('');
    }, []);

    const patientList = async (a = search , c = page) => {


        if(c !== page){
            setPage(c);
        }
        if(a !== search){
            setSearch(a);
            setPage(1)
            c = 1
        }
        await axios({
            url : '/data/facture/list?query='+a+'&page='+c,
            method: 'GET'
        }).then(r => {
            setFactures(r.data.impaye.data)
            setPagination(r.data.impaye)
        })

    }

    const postFacture = async () => {
        await axios({
            method: 'POST',
            url: '/data/facture/add',
            data:{
                name,
                montant,
                payed
            }
        }).then(r => {
            if(r.status === 201) {
                setName("")
                setMontant('')
                setPayed(false)
                displayPopup(false);
                patientList();
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
                <PageNavigator prev={()=> {patientList(search,page-1)}} next={()=> {patientList(search,page+1)}} prevDisabled={(paginate.prev_page_url === null)} nextDisabled={(paginate.next_page_url === null)}/>
                <Searcher value={search} callback={(v) => {patientList(v)}}/>
                <div className={'exporter'}>
                    <div className={'exporter-part'}>
                        <label>du</label>
                        <input type={'date'} value={from} onChange={(e)=>{setFrom(e.target.value)}}/>
                    </div>
                    <div className={'exporter-part'}>
                        <label>au</label>
                        <input type={'date'} value={to} onChange={(e)=>{setTo(e.target.value)}}/>
                    </div>
                    {(user.grade.admin || user.grade.facture_export) &&
                        <a href={'/PDF/facture/' + from  +'/'+to}  target={'_blank'} className={'bnt'}><img alt={''} src={'/assets/images/pdf.png'}/></a>
                    }

                </div>
                <button className={'btn'} disabled={!(user.grade.admin || user.facture_create)} onClick={()=>{displayPopup(true)}}>ajouter</button>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>date</th>
                            <th>montant</th>
                            <th/>
                        </tr>
                    </thead>
                    <tbody>
                    {factures && factures.map((item) =>
                        <tr key={item.id}>
                            <td>{item.id}</td>
                            <td className={'clickable'}><Link to={'/patients/' + item.getpatient.id +'/view'}>{item.getpatient.name}</Link></td>
                            <td>{item.created_at}</td>
                            <td>${item.price}</td>
                            <td>{!item.payed && <button className={'btn'} disabled={!(user.grade.admin || user.grade.facture_paye)} onClick={async () => {
                                await axios({
                                    method: 'PUT',
                                    url: '/data/facture/' + item.id + '/paye'
                                }).then(() => {
                                    patientList()
                                })
                            }}>payer</button>}
                                {item.payed &&
                                    <p>payée</p>
                                } </td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
        </div>
        {popupDisplayed &&
            <section className={'popup'}>
                <CardComponent  title={'ajouter une facture'}>
                    <div className={'form-group form-column'}>
                        <label>prénom nom</label>
                        <input type={'text'} className={'form-input'} list={'autocomplete'} value={name} onChange={(e)=>{searchPatient(e.target.value)}}/>
                        {patientDataList &&
                            <datalist id={'autocomplete'} >
                                {patientDataList.map((item)=>
                                    <option key={item.id}>{item.name}</option>
                                )}
                            </datalist>
                        }
                        {errors.name &&
                            <div className={'errors-list'}>
                                <ul>
                                    {errors.name.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-group form-column'}>
                        <label>montant</label>
                        <input type={'number'} className={'form-input'} value={montant} onChange={(e)=>{setMontant(e.target.value)}}/>
                        {errors.montant &&
                            <div className={'errors-list'}>
                                <ul>
                                    {errors.montant.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-group form-line'}>
                        <label>Payé : </label>
                        <SwitchBtn number={'A1'} checked={payed} callback={()=>{setPayed(!payed)}}/>
                    </div>
                    <div className={'form-group form-line'}>
                        <button className={'btn'} onClick={()=>{
                            setName('');
                            setPayed(false)
                            setMontant('');
                        }}>effacer</button>
                        <button className={'btn'} onClick={postFacture}>envoyer</button>
                        <button className={'btn'} onClick={()=>{displayPopup(false)}}>fermer</button>
                    </div>
                </CardComponent>
            </section>
        }

    </div> )
}

export default FactureList;
