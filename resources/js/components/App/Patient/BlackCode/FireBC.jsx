import React, {useEffect, useState} from 'react';
import PatientList from "./PatientList";
import SwitchBtn from "../../../props/SwitchBtn";
import {set} from "lodash/object";
import CardComponent from "../../../props/CardComponent";
import {useParams} from "react-router-dom";
import axios from "axios";

function FireBC(props) {
    const [bc , setBC] = useState([]);
    const {bcID} = useParams();
    const [blessures, setBlessures] = useState([]);
    const [caserne, setCaserne] = useState("");
    const [place, setPlace] = useState("");

    const [payed, setPayed] = useState(false);
    const [pName, setPName] = useState("");
    const [blessure, setblessure] = useState(0);
    const [searching, setsearching] = useState([]);
    const [errors, setErrors] = useState([])

    const [searchPersonnel, setPersonnelSearcher] = useState("");
    const [persoSearchedList, setPersoSearchedList] = useState("");

    const [description, setDescription] = useState("");

    const Redirection = (url) => {
        props.history.push(url)
    }

    const SearchingPersonnel = async (v) => {
        setPersonnelSearcher(v);
        if (v.length > 2) {
            await axios({
                method: 'GET',
                url: '/data/users/search/' + v
            }).then(r => {
                setPersoSearchedList(r.data.users);
            })
        }
    }

    const searchPatient = async (search) => {
        setPName(search)
        if(search.length > 0){
            await axios({
                method: 'GET',
                url: '/data/patient/search/'+search,
            }).then((response)=>{
                setsearching(response.data.patients);
            })
        }
    }

    useEffect(()=>{
        pool();

        let GlobalChannel = window.GlobalChannel;
        GlobalChannel.bind('BlackCodeUpdated',(e) => {
            if("" + e.id === "" + bcID){
                pool();
            }
        });

        return () => {
            GlobalChannel.unbind('BlackCodeUpdated');
        }
    }, [])

    const pool = async () => {
        await  axios({
            url : '/data/blackcode/' + bcID +'/infos',
            method: 'get',
        }).then(r => {
            setBC(r.data.bc)
            setBlessures(r.data.blessures)
            setCaserne(r.data.bc.caserne);
            setDescription(r.data.bc.description)
            setPlace(r.data.bc.place)
        })
    }

    const postPatient = async () => {
        await axios({
            method: 'POST',
            url : '/data/blackcode/' + bcID + '/add/patient',
            data : {
                'name': pName,
                blessure,
                payed,
            }
        }).then(r => {
            if(r.status === 201) {
                setPName("")
                setblessure(0)
                setPayed(false)
            }
        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })

    }

    return (<div className={'BC-View'}>
        <section className={'BC-Header'}>
            <div className={'BC-Place'}>
                <h5>{bc.place ? bc.place + ' ' + (bc.ended ? '(terminé)' : '(en cours)') : 'chargement'}</h5>
            </div>
            <div className={'BC-Starter'}>
                <h5>{bc.place ? bc.get_user.name : 'chargement'}</h5><img alt={''} src={'/assets/images/'+ (bc.place ?  bc.get_user.service : '') + '.png'}/>
            </div>
            <div className={'BC-Commands'}>
                <button  className={'btn'} onClick={async () => {
                    await axios({method: 'PATCH', url: '/data/blackcode/quit'}).then(() => {
                        Redirection('/blackcodes/all')
                    })
                }}>retour</button>
                <button  className={'btn'} onClick={async () => {
                    await axios({
                        method: 'PUT',
                        url: '/data/blackcode/' + bcID + '/close'
                    }).then(r => {
                        if(r.status === 202){
                            Redirection('/blackcodes/all')
                        }
                    })
                }}>terminer</button>
                <button  className={'btn'}><img alt={''} src={'/assets/images/pdf.png'}/></button>
            </div>
        </section>
        <section className={'BC-Content'}>
            <section className={'BC-infos'}>
                <div className={'BC-infosForm'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Information</label>
                        <button className={'btn img'}><img src={'/assets/images/save.png'} alt={''} onClick={async () => {
                            await axios({
                                method: 'PATCH',
                                url :'/data/blackcode/'+ bcID +'/infos',
                                data: {
                                    caserne,
                                    place
                                }
                            })
                        }}/></button>
                    </div>

                    <div className={'form-group form-column'}>
                        <label>Lieux</label>
                        <input type={'text'} value={place} onChange={e => {
                            setPlace(e.target.value)
                        }}/>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Déclenchement</label>
                        <input type={"text"} disabled={true}  value={(bc.place ? bc.created_at : '')}/>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Caserne envoyé</label>
                        <input type={'text'} value={caserne} onChange={e => {
                            setCaserne(e.target.value)
                        }}/>
                    </div>
                </div>
                <div className={'BC-personnel'}>
                    <div className={'personnel-adder form-line form-group'}>
                        <input type={"text"} placeholder={'prénom nom'} list={"personnel"} value={searchPersonnel} onChange={(e) => {
                            SearchingPersonnel(e.target.value)
                        }}/>
                        {persoSearchedList &&
                            <datalist id={'personnel'} >
                                {persoSearchedList.map((item)=>
                                    <option key={item.id}>{item.name}</option>
                                )}
                            </datalist>
                        }
                        <button className={'btn'} onClick={async () => {
                            await axios({
                                method: 'POST',
                                url: '/data/blackcode/' + bcID + '/add/personnel/'+searchPersonnel,
                            })
                        }}>ajouter</button>
                    </div>
                    <ul className={'Personnel-list'}>
                        {bc.place && bc.get_personnel.map((perso) =>
                            <li className={'personnel-tag'} key={perso.id}>
                                <h6>{perso.name} </h6> <img alt={''} src={'/assets/images/' + perso.service + '.png'}/>
                            </li>
                        )}
                    </ul>
                </div>
            </section>
            <section className={'BC-Patient'}>
                <div className={'BC-PatientAdder'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Ajouter un patient</label>
                        <button className={'btn'} onClick={postPatient}>ajouter</button>
                        <button className={'btn'} onClick={()=>{
                            setPName("")
                            setblessure(0)
                            setPayed(false)
                        }}>effacer</button>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>prénom nom</label>
                        <input type={'text'} className={'form-input'} list={'autocomplete'} value={pName} onChange={(e)=>{searchPatient(e.target.value)}}/>
                        {searching &&
                            <datalist id={'autocomplete'} >
                                {searching.map((item)=>
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
                        <label >Type de blessure</label>
                        <select onChange={(e)=>{setblessure(e.target.value)}} value={blessure}>
                            <option disabled={true} value={0}>chosir</option>
                            {blessures && blessures.map((kc) =>
                                <option key={kc.id} value={kc.id}>{kc.name}</option>
                            )}
                            {errors.blessure &&
                                <div className={'errors-list'}>
                                    <ul>
                                        {errors.blessure.map((error) =>
                                            <li>{error}</li>
                                        )}
                                    </ul>
                                </div>
                            }

                        </select>
                    </div>
                    <div className={'form-group form-line'}>
                        <label>Payé : </label>
                        <SwitchBtn number={'A1'} checked={payed} callback={()=>{setPayed(!payed)}}/>
                    </div>
                </div>
                <div className={'BC-InetDetails'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Détails de l'intervetion</label>
                        <button className={'btn img'}><img src={'/assets/images/save.png'} onClick={async () => {
                            await axios({
                                method: 'PATCH',
                                url : '/data/blackcode/' + bcID + '/desc',
                                data: {
                                    description,
                                }
                            })
                        }}alt={''}/></button>
                    </div>
                    <textarea value={description} onChange={(e)=>{setDescription(e.target.value)}}/>
                </div>

            </section>
            <div className={'patientList'}>
                <CardComponent title={'Liste de patient (1)'}>
                    <div className={'patient-listing'}>
                        <table>
                            <tbody>
                            {bc.place && bc.get_patients.map((p)=>
                                <tr>
                                    <td className={'name clickable'} onClick={()=>{Redirection('/patients/'+p.patient_id+'/view')}}>{p.name}</td>
                                    <td className={'date'}>{p.created_at}</td>
                                    <td className={'action'}><button className={'btn'} onClick={()=>{Redirection('/patients/' + p.patient_id + '/view?id='+p.rapport_id)}}><img alt={''} src={'/assets/images/documents.png'}/></button> <button className={'btn'} onClick={()=>{
                                        axios({
                                            method:  'DELETE',
                                            url : '/data/blackcode/delete/patient/'+p.id
                                        })
                                    }}><img alt={''} src={'/assets/images/decline.png'}/></button></td>
                                </tr>
                            )}

                            </tbody>
                        </table>
                    </div>
                </CardComponent>
            </div>
        </section>
    </div> )
}

export default FireBC;
