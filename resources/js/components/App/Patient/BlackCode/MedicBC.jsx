import React, {useContext, useEffect, useState} from 'react';
import SwitchBtn from "../../../props/SwitchBtn";
import {useLocation, useParams} from "react-router-dom";
import CardComponent from "../../../props/CardComponent";
import PatientList from "./PatientList";
import axios from "axios";
import UserContext from "../../../context/UserContext";

function MedicBC(props) {


    const [bc , setBC] = useState([]);
    const [colors, setColors] = useState([]);
    const {bcID} = useParams();
    const [blessures, setBlessures] = useState([]);
    const [caserne, setCaserne] = useState("");
    const user = useContext(UserContext);

    const [payed, setPayed] = useState(false);
    const [pName, setPName] = useState("");
    const [blessure, setblessure] = useState(0);
    const [color, setColor] = useState(0);
    const [id, setId] = useState(false);
    const [searching, setsearching] = useState([]);
    const [errors, setErrors] = useState([])

    const [description, setDescription] = useState("");

    const Redirection = (url) => {
        props.history.push(url)
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
            setColors(r.data.colors)
            setBlessures(r.data.blessures)
            setCaserne(r.data.bc.caserne);
            setDescription(r.data.bc.description)
        })
    }

    const postPatient = async () => {
        await axios({
            method: 'POST',
            url : '/data/blackcode/' + bcID + '/add/patient',
            data : {
                'name': pName,
                'carteid':id,
                blessure,
                color,
                payed,
            }
        }).then(r => {
            if(r.status === 201) {
                setPName("")
                setblessure(0)
                setColor(0)
                setId(false)
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
                    })}}>retour</button>
                <button  className={'btn'} disabled={(!(user.grade.admin || user.grade.BC_close) || (bc && bc.ended))}  onClick={async () => {
                    await axios({
                        method: 'PUT',
                        url: '/data/blackcode/' + bcID + '/close'
                    }).then(r => {
                        if(r.status === 202){
                            Redirection('/blackcodes/all')
                        }
                    })
                }}>terminer</button>
                <a  target={"_blank"} href={'/pdf/bc/'+bcID} className={'btn'}><img alt={''} src={'/assets/images/pdf.png'}/></a>
            </div>
        </section>
        <section className={'BC-Content'}>
            <section className={'BC-infos'}>
                <div className={'BC-infosForm'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Information</label>
                        <button className={'btn img'} disabled={!(user.grade.admin || user.grade.BC_edit)} onClick={async () => {
                            await axios({
                                method: 'PATCH',
                                url : '/data/blackcode/' + bcID + '/caserne',
                                data: {
                                    caserne,
                                }
                            })
                        }}><img src={'/assets/images/save.png'} alt={''}/></button>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Type de Black Code</label>
                        <input type={'text'} value={(bc.place ? bc.get_type.name : '')} disabled={true}/>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Caserne envoyé</label>
                        <input type={'text'} value={caserne} onChange={(v)=>{setCaserne(v.target.value)}}/>
                    </div>
                </div>
                <div className={'BC-personnel'}>
                    <ul className={'Personnel-list'}>
                        {bc.place && bc.get_personnel.map((perso) =>
                            <li className={'personnel-tag'} key={perso.id}>
                                <h6>{perso.name} </h6> <img alt={''} src={'/assets/images/' + perso.service + '.png'}/>
                            </li>
                        )}
                    </ul>
                </div>
                <div className={'BC-InetDetails'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Détails de l'intervetion</label>
                        <button className={'btn img'} disabled={!(user.grade.admin || user.grade.BC_edit)} onClick={async () => {
                            await axios({
                                method: 'PATCH',
                                url : '/data/blackcode/' + bcID + '/desc',
                                data: {
                                    description,
                                }
                            })
                        }}><img src={'/assets/images/save.png'} alt={''}/></button>
                    </div>
                    <textarea value={description} onChange={(e)=>{setDescription(e.target.value)}}/>
                </div>
            </section>
            <section className={'BC-Patient'}>
                <div className={'BC-PatientAdder'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Ajouter un patient</label>
                        <button className={'btn'} onClick={postPatient} disabled={!(user.grade.admin || user.grade.BC_modify_patient)}>ajouter</button>
                        <button className={'btn'} onClick={()=>{
                            setPName("")
                            setblessure(0)
                            setColor(0)
                            setId(false)
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
                    <div className={'form-group form-column'}>
                        <label>Couleur vètement</label>
                        <select onChange={(e)=>{setColor(e.target.value)}} value={color}>
                            <option disabled={true} value={0}>chosir</option>
                            {colors && colors.map((kc) =>
                                <option key={kc.id} value={kc.id}>{kc.name}</option>
                            )}
                            {errors.color &&
                                <div className={'errors-list'}>
                                    <ul>
                                        {errors.color.map((error) =>
                                            <li>{error}</li>
                                        )}
                                    </ul>
                                </div>
                            }
                        </select>
                    </div>
                    <div className={'form-group form-line'}>
                        <label>Carte Id : </label>
                        <SwitchBtn number={'A0'} checked={id} callback={()=>{setId(!id)}}/>
                    </div>
                    <div className={'form-group form-line'}>
                        <label>Payé : </label>
                        <SwitchBtn number={'A1'} checked={payed} callback={()=>{setPayed(!payed)}}/>
                    </div>
                    {searching.length === 1 &&
                        <div className={'PatientOtherInfos'}>
                            <div className={'form-group form-line'}>
                                <label>Date de naissance : <span>{searching[0].naissance}</span></label>
                            </div>
                            <div className={'form-group form-line'}>
                                <label>Groupe saunguin : <span>{searching[0].blood_group}</span></label>
                            </div>
                            <div className={'form-group form-line'}>
                                <label>Tel : <span>{searching[0].tel}</span></label>
                            </div>
                        </div>
                    }

                </div>


            </section>
            <PatientList list={bc.get_patients} history={props.history}/>
        </section>
    </div>  )
}

export default MedicBC;
