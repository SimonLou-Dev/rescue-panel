import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import CardComponent from "../../../props/CardComponent";
import SwitchBtn from "../../../props/SwitchBtn";
import Button from "../../../props/Button";
import UserContext from "../../../context/UserContext";


function Rapport(props) {
    const [name, setName] = useState();
    const [ddn , setDDn] = useState();
    const [tel, setTel] = useState();
    const [liveplace, setLiveplace] = useState();
    const [interdate, setinterdate] = useState();
    const [interhour, setinterhour]= useState();
    const [lieux, setlieux] = useState();
    const [intertype, setintertypes] = useState(0);
    const [transport, settransport] = useState(0);
    const [ata, setata] = useState();
    const [montant, setmontant] = useState();
    const [payed, setpayed] = useState(false);
    const [desc, setdesc] = useState();

    const [impaye, setImpaye] = useState(null);
    const [impayePhrase, setImpayePhrase] = useState(null);

    const [bloodgroup, setbloodgroup] = useState();
    const [pathology, setpathology] = useState(0);

    const [pathologysList, setpathologysList] = useState();
    const [transportlist, settransportlist] = useState();
    const [intertypeslist ,setintertypeslist] = useState();

    const [erros, seterros] = useState([]);
    const [searching, setsearching] = useState();
    const [patient, setpatient] = useState();
    const user = useContext(UserContext);


    useEffect(async ()=>{
        await axios({
            method: 'GET',
            url: '/data/rapport/getforinter'
        }).then((resp)=>{
            settransportlist(resp.data.transport);
            setintertypeslist(resp.data.intertype);
            setpathologysList(resp.data.pathology)
        })

    }, [])

    const getImpaye = async (patientId)=>{
        await  axios({
            method: 'GET',
            url: '/data/patient/'+ patientId + '/impaye'
        }).then((r)=>{
            if(r.data.number === 0){
                setImpaye(false);
                setImpayePhrase('aucun impayé auprès de l\'OMC ');
            }else{
                setImpaye(true);
                setImpayePhrase(r.data.number +  " factures à payer ($" + r.data.montant + ")");
            }
        })

    }


    const searchPatient = async (search) => {
        if(search.length > 0){
            await axios({
                method: 'GET',
                url: '/data/patient/search/'+search,
            }).then((response)=>{
                setsearching(response.data.patients);
                if (response.data.patients.length === 1 && response.data.patients[0].name === search) {
                    let patient = response.data.patients[0];
                    setName(patient.name);
                    setDDn(patient.naissance);
                    setTel(patient.tel);
                    setLiveplace(patient.living_place);
                    setbloodgroup(patient.blood_group);
                    getImpaye(patient.id)
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
                bloodgroup: bloodgroup,
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
                setName('');
                setinterdate('')
                setinterhour('')
                setTel('')
                setLiveplace('')
                setlieux('')
                setintertypes(0)
                settransport(0)
                setpathology(0)
                setdesc('')
                setDDn('');
                setmontant('');
                setpayed(false)
                setata('')
                setbloodgroup('')
            }

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
                            <datalist id={'autocomplete'} >
                                {searching.map((item)=>
                                    <option key={item.id}>{item.name}</option>
                                )}
                            </datalist>
                        }
                        {erros.name &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.name.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }

                    </div>
                    <div className={'form-item form-column'}>
                        <label>date de naissance</label>
                        <input type={'date'} className={'form-input'} value={ddn} onChange={(e)=>{setDDn(e.target.value)}}/>
                        {erros.ddn &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.ddn.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-item form-column'}>
                        <label>téléphone</label>
                        <input type={'text'} className={'form-input'} value={tel} onChange={(e)=>{setTel(e.target.value)}}/>
                        {erros.tel &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.tel.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-item form-column'}>
                        <label>Lieux de vie</label>
                        <input type={'text'} className={'form-input'} value={liveplace} onChange={(e)=>{setLiveplace(e.target.value)}}/>
                        {erros.liveplace &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.liveplace.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-item form-column'}>
                        <label>Groupe saunguin</label>
                        <input type={'text'} className={'form-input'} value={bloodgroup} onChange={(e)=>{setbloodgroup(e.target.value)}}/>
                        {erros.bloodgroup &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.bloodgroup.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    {impaye != null &&
                        <h5 className={!impaye ? 'cool' : 'pascool'}>{impayePhrase}</h5>
                    }
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'Intervention'}>
                    <div className={'form-item form-column'}>
                        <label>Date et heure</label>
                        <input type={'date'} className={'form-input'} value={interdate} onChange={(e)=>{setinterdate(e.target.value)}}/>
                        <input type={'time'} className={'form-input'} value={interhour} onChange={(e)=>{setinterhour(e.target.value)}}/>
                        {erros.startinter &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.startinter.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                    <div className={'form-item form-column'}>
                        <label>Lieux</label>
                        <input type={'text'} className={'form-input'} value={lieux} onChange={(e)=>{setlieux(e.target.value)}}/>
                        {erros.lieux &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.lieux.map((error) =>
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
                        {erros.intertype &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.intertype.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                </CardComponent>
            </div>
            <div className={'collumn'}>
                <CardComponent title={'ATA'}>
                    <div className={'form-item form-column'}>
                        <label>ATA</label>
                        <input type={'text'} className={'form-input'} value={ata} onChange={(e)=>{setata(e.target.value)}}/>
                        <label className={'form-healper'}>ex: 13h 3m</label>
                        {erros.ata &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.ata.map((error) =>
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
                        {erros.montant &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.montant.map((error) =>
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
            </div>
            <div className={'collumn'}>
                <CardComponent title={'Transport'}>
                    <div className={'form-item form-column'}>
                        <label>Transport</label>
                        <select value={transport} onChange={(e)=>{settransport(e.target.value)}}>
                            <option key={0} value={0} disabled={true}>choisir</option>
                            {transportlist && transportlist.map((broum)=>
                                <option key={broum.id} value={broum.id}> transport : {broum.name}</option>
                            )}
                        </select>
                        {erros.transport &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.transport.map((error) =>
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
                            {erros.transport &&
                                <div className={'errors-list'}>
                                    <ul>
                                        {erros.transport.map((error) =>
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
                        {erros.desc &&
                            <div className={'errors-list'}>
                                <ul>
                                    {erros.desc.map((error) =>
                                        <li>{error}</li>
                                    )}
                                </ul>
                            </div>
                        }
                    </div>
                </CardComponent>
            </div>
        </div>
    )
}

export default Rapport;
