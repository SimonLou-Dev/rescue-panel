import React, {useContext, useEffect, useState} from 'react';
import CardComponent from "../../../props/CardComponent";
import SwitchBtn from "../../../props/SwitchBtn";
import {useLocation, useParams} from "react-router-dom";
import axios from "axios";
import UserContext from "../../../context/UserContext";
import dateFormat from "dateformat";
import Button from "../../../props/Button";

function RapportReview(props) {
    const [interdate, setinterdate] = useState();
    const [interhour, setinterhour]= useState();
    const [intertype, setintertype] = useState();
    const [transport, settransport] = useState();
    const [ata, setata] = useState();
    const [montant, setmontant] = useState();
    const [payed, setpayed] = useState(false);
    const [desc, setdesc] = useState();
    const [pathology, setpathology] = useState(0);

    const {patientId} = useParams()
    const [pathologysList, setpathologysList] = useState([]);
    const [transportlist, settransportlist] = useState([]);
    const [intertypeslist ,setintertypeslist] = useState([]);
    const [errors, setErrors] = useState([])
    const user = useContext(UserContext);
    const [interList, setInterList] = useState([]);
    const search = useLocation().search;
    const rappportId = new URLSearchParams(search).get('id');

    const [interSelected, selectInter] = useState(null)

    const Redirection = (url) => {
        props.history.push(url)
    }

    useEffect(()=>{
        findData();
    }, [])



    const findData = async () => {
        await  axios({
            method: 'GET',
            url: '/data/rapport/get/'+patientId
        }).then((r)=>{
            let final = [];
            let keys = Object.keys(r.data.rapportlist);
            keys.forEach((key) => {
                final[key] = r.data.rapportlist[key];
            });
            setpathologysList(r.data.pathologys)
            setintertypeslist(r.data.types)
            settransportlist(r.data.broum)
            setInterList(final)
            if(rappportId){
                selectrapport(rappportId, final);
            }
        })
    }

    const selectrapport = (id, rapportlist) => {
        let rapport = undefined;
        if(interList[id] === undefined && rapportlist[id] !== undefined){
            rapport = rapportlist[id]
        }else{
            rapport = interList[id];
        }

        let separatedDate = rapport.started_at.split(' ');
        setinterhour(separatedDate[1])
        setinterdate(separatedDate[0])
        setintertype(rapport.interType)
        settransport(rapport.transport)
        setata(rapport.ata)
        setpathology(rapport.pathology_id)
        setmontant(rapport.price)
        setpayed(rapport.get_facture.payed)
        setdesc(rapport.description)
        selectInter(id);
    }

    const postRapport = async () =>{

        await axios({
            url: '/data/rapport/update/'+interSelected,
            method: 'PUT',
            data: {
                startinter: interdate + ' '  + interhour,
                type: intertype,
                transport: transport,
                desc: desc,
                montant: montant,
                payed: payed,
                ata: ata,
                pathology: pathology,
            }
        }).then(response => {
            if(response.status === 201){
                setinterdate('')
                setinterhour('')
                setintertype(0)
                settransport(0)
                setpathology(0)
                setdesc('')
                setmontant('');
                setpayed(false)
                setata('')
                findData()
            }

        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })

    }

    return (<div className={"RapportReview"}>
        <div className={'fixed-top-right'}>
            <Button value={'envoyer'} callback={postRapport}/>
        </div>
        <section className={'interList'}>
            <CardComponent title={'Interventions'}>
                <div className={'intervention-table'}>
                    {interList && interList.map((inter)=>
                        <div className={'inter-item'} key={inter.id}>
                            <h5 onClick={()=>{selectrapport(inter.id)}}>{inter.service} {dateFormat(inter.started_at, 'dd/mm/yyyy HH:MM')} #{inter.id}</h5>
                        </div>
                    )}

                </div>
                <div className={'navigation'}>
                    <button className={'btn'} onClick={()=>{Redirection('/patients/rapport?patientId='+patientId)}}>nouveau</button>
                    <button className={'btn'} onClick={()=>{Redirection('/patients/dossiers')}}>retour</button>
                    {interSelected != null &&
                        <a href={'/pdf/rapport/'+interSelected} target={'_blank'} className={'btn exporter'}>
                            <img src={'/assets/images/pdf.png'} alt={''}/>
                        </a>
                    }

                </div>

            </CardComponent>
        </section>
        <section className={'intervention'}>

            <CardComponent title={'Intervention'}>
                <div className={'form-item form-column'}>
                    <label>Date et heure</label>
                    <input type={'date'} className={'form-input'} value={interdate} onChange={(e)=>{setinterdate(e.target.value)}}/>
                    <input type={'time'} className={'form-input'} value={interhour} onChange={(e)=>{setinterhour(e.target.value)}}/>
                    {errors.startinter &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.startinter.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
                <div className={'form-item form-column'}>
                    <label>Type d'intervention</label>
                    <select value={intertype} onChange={(e)=>{setintertypes(e.target.value)}}>
                        <option key={0} value={0} disabled={true}>choisir</option>
                        {intertypeslist && intertypeslist.map((broum)=>
                            <option key={broum.id} value={broum.id}>{broum.name}</option>
                        )}
                    </select>
                    {errors.intertype &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.intertype.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
            </CardComponent>
            <CardComponent title={'ATA'}>
                <div className={'form-item form-column'}>
                    <label>ATA</label>
                    <input type={'text'} className={'form-input'} value={ata} onChange={(e)=>{setata(e.target.value)}}/>
                    <label className={'form-healper'}>ex: 13h 3m</label>
                    {errors.ata &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.ata.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
            </CardComponent>
            <CardComponent title={'Facturation'}>
                <div className={'form-item form-column'}>
                    <label>Montant (en $)</label>
                    <input type={'number'} className={'form-input'} value={montant} onChange={(e)=>{setmontant(e.target.value)}}/>
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
                <div className={'form-item form-line'}>
                    <label>Payé</label>
                    <SwitchBtn number={"a0"} checked={payed} callback={(e)=>{setpayed(!payed)}}/>
                </div>
            </CardComponent>

            <CardComponent title={'Transport'}>
                <div className={'form-item form-column'}>
                    <label>Transport</label>
                    <select value={transport} onChange={(e)=>{settransport(e.target.value)}}>
                        <option key={0} value={0} disabled={true}>choisir</option>
                        {transportlist && transportlist.map((broum)=>
                            <option key={broum.id} value={broum.id}> transport : {broum.name}</option>
                        )}
                    </select>
                    {errors.transport &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.transport.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
            </CardComponent>
            {user.service === 'OMC' &&
                <CardComponent title={'Pathologie'}>
                    <div className={'form-item form-column'}>
                        <select value={pathology} onChange={(e) => {
                            setpathology(e.target.value)
                            let place;
                            for( let i = 0; i < pathologysList.length;  i++){
                                if(""+pathologysList[i].id  === e.target.value){
                                    place = i;
                                }
                            }
                            if(place !== undefined){
                                setdesc(pathologysList[place].desc)
                            }

                        }}>
                            <option key={0} value={0} disabled={true}>choisir</option>
                            {pathologysList && pathologysList.map((broum) =>
                                <option key={broum.id} value={broum.id}>{broum.name}</option>
                            )}
                        </select>
                        {errors.transport &&
                            <div className={'errors-list'}>
                                <ul>
                                    {errors.transport.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                </CardComponent>
            }
            <CardComponent title={'Description'}>
                <div className={'form-item form-column'}>
                    <textarea className={'form-input'} rows={10} value={desc} onChange={(e)=>{setdesc(e.target.value)}}/>
                    {errors.desc &&
                        <div className={'errors-list'}>
                            <ul>
                                {errors.desc.map((error) =>
                                    <li>{error}</li>
                                )}
                            </ul>
                        </div>
                    }
                </div>
            </CardComponent>
        </section>






    </div> )
}

export default RapportReview;
