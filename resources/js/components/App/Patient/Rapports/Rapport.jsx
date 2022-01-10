import React, {useEffect, useState} from 'react';
import axios from "axios";
import CardComponent from "../../../props/CardComponent";
import SwitchBtn from "../../../props/SwitchBtn";
import Button from "../../../props/Button";


function Rapport(props) {
    const [name, setName] = useState();
    const [ddn , setDDn] = useState();
    const [tel, setTel] = useState();
    const [liveplace, setLiveplace] = useState();
    const [interdate, setinterdate] = useState();
    const [interhour, setinterhour]= useState();
    const [lieux, setlieux] = useState();
    const [intertype, setintertypes] = useState();
    const [transport, settransport] = useState();
    const [ata, setata] = useState();
    const [montant, setmontant] = useState();
    const [payed, setpayed] = useState(false);
    const [desc, setdesc] = useState();

    const [transportlist, settransportlist] = useState();
    const [intertypeslist ,setintertypeslist] = useState();

    const [erros, seterros] = useState();
    const [searching, setsearching] = useState();
    const [patient, setpatient] = useState();


    useEffect(async ()=>{
        await axios({
            method: 'GET',
            url: '/data/rapport/getforinter'
        }).then((resp)=>{
            settransportlist(resp.data.transport);
            setintertypeslist(resp.data.intertype);
        })

    }, [])


    const searchPatient = async (search) => {
        if(search.length > 0){
            await axios({
                method: 'GET',
                url: '/data/patient/search/'+search,
            }).then((response)=>{
                setsearching(response.data.patients);
                if (response.data.patients.length === 1) {
                    let patient = response.data.patients[0];
                    setName(patient.vorname + ' ' + patient.name);
                    setDDn(patient.naissance);
                    setTel(patient.tel);
                    setLiveplace(patient.living_place);
                }
                if (response.data.patients.length === 0) {
                    setName('');
                    setDDn('');
                    setTel('');
                    setLiveplace('');
                }

            })
        }
    }

    const postRapport = async () =>{

        await axios({
            url: '/data/rapport/post',
            method: 'POST',
            data: {
                name: name,
                startinter: interdate + ' '  + interhour,
                tel: tel,
                ddn: ddn,
                liveplace: liveplace,
                lieux: lieux,
                type: intertype,
                transport: transport,
                desc: desc,
                montant: montant,
                payed: payed,
                ata: ata,
            }
        }).then(response => {
            setName('');
            setinterdate('')
            setinterhour('')
            setTel('')
            setLiveplace('')
            setlieux('')
            setintertypes('')
            settransport('')
            setdesc('')
            setDDn('');
            setmontant('');
            setpayed(false)
            setata('')

        }).catch(error => {
            if(error.response.status === 422){
                seterros(error.response.data.errors)
            }
        })

    }


    return (
        <div className={"rapports"}>
            <div className={'fixed-top-right'}>
                <Button value={'envoyer'} callback={postRapport}/>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'Patient'}>
                    <div className={'form-item form-column'}>
                        <label>prénom nom</label>
                        <input type={'text'} className={'form-input'} list={'autocomplete'} value={name} onChange={(e)=>{setName(e.target.value), searchPatient(e.target.value)}}/>
                        {searching &&
                            <datalist id={'autocomplete'}>
                                {searching.map((item)=>
                                    <option key={item.id}>{item.vorname} {item.name}</option>
                                )}
                            </datalist>
                        }
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>date de naissance</label>
                        <input type={'date'} className={'form-input'} value={ddn} onChange={(e)=>{setDDn(e.target.value)}}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>téléphone</label>
                        <input type={'text'} className={'form-input'} value={tel} onChange={(e)=>{setTel(e.target.value)}}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>Lieux de vie</label>
                        <input type={'text'} className={'form-input'} value={liveplace} onChange={(e)=>{setLiveplace(e.target.value)}}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'Intervention'}>
                    <div className={'form-item form-column'}>
                        <label>Date et heure</label>
                        <input type={'date'} className={'form-input'} value={interdate} onChange={(e)=>{setinterdate(e.target.value)}}/>
                        <input type={'time'} className={'form-input'} value={interhour} onChange={(e)=>{setinterhour(e.target.value)}}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>Lieux</label>
                        <input type={'text'} className={'form-input'} value={lieux} onChange={(e)=>{setlieux(e.target.value)}}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-column'}>
                        <label>Type d'intervention</label>
                        <select value={intertype} onChange={(e)=>{setintertypes(e.target.value)}}>
                            {intertypeslist && intertypeslist.map((broum)=>
                                <option key={broum.id} value={broum.id}>{broum.name}</option>
                            )}
                        </select>
                    </div>
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'ATA'}>
                    <div className={'form-item form-column'}>
                        <label>ATA</label>
                        <input type={'text'} className={'form-input'} value={ata} onChange={(e)=>{setata(e.target.value)}}/>
                        <label className={'form-healper'}>ex: 13h 3m</label>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
                <CardComponent title={'Facturation'}>
                    <div className={'form-item form-column'}>
                        <label>Montant (en $)</label>
                        <input type={'number'} className={'form-input'} value={montant} onChange={(e)=>{setmontant(e.target.value)}}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                    <div className={'form-item form-line'}>
                        <label>Payé</label>
                        <SwitchBtn number={"a0"} checked={payed} callback={(e)=>{setpayed(!payed)}}/>
                    </div>
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'Transport'}>
                    <div className={'form-item form-column'}>
                        <label>Transport</label>
                        <select value={transport} onChange={(e)=>{settransport(e.target.value)}}>
                            {transportlist && transportlist.map((broum)=>
                                <option key={broum.id} value={broum.id}> transport : {broum.name}</option>
                            )}
                        </select>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
                <CardComponent title={'Description'}>
                    <div className={'form-item form-column'}>
                        <textarea className={'form-input'} rows={10} value={desc} onChange={(e)=>{setdesc(e.target.value)}}/>
                        <div className={'errors-list'}>
                            <ul>
                                <li>test</li>
                            </ul>
                        </div>
                    </div>
                </CardComponent>
            </div>
        </div>
    )
}

export default Rapport;
