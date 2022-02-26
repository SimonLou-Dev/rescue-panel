import React, {useContext, useEffect, useState} from 'react';
import CardComponent from "../../props/CardComponent";
import UserContext from "../../context/UserContext";
import {useLocation, useParams} from "react-router-dom";
import axios from "axios";

function FichePersonnel(props) {
    const [sanctionPopup, setSanctionPopup] = useState(false);
    const [materialPopup, setMaterialPopup] = useState(false);
    const [sanction, setSanction] = useState(0);
    const [MAP, setMAP] = useState('');
    const [note_lic, setnote_lic] = useState('');
    const [reason, setReason] = useState('');
    const [note, setNote] = useState('');

    const [material, setMaterial] = useState(null);

    const me = useContext(UserContext);
    const {userId} = useParams();

    const [user, setUser] = useState(null);

    useEffect(async () => {
        update();


        },
        [])

    const update = async () => {
        await axios({
            method: 'GET',
            url: '/data/user/' + userId + '/sheet'
        }).then(r => {
            if(r.data.user.materiel === null){
                r.data.user.materiel = {
                    'extincteur':false,
                    'flashlight':false,
                    'flare':false,
                    'kevlar':false,
                    'flaregun':false
                };
            }
            setUser(r.data.user);
            setMaterial(r.data.user.materiel);
        })
    }

    return (<div className={'FichePersonnel'}>
        <section className={'left-part ' + (sanctionPopup ? 'popupBg':'') + (materialPopup ? 'popupBg':'') }>
            <section className={'btn-container'}>
                <button className={'btn --medium'} onClick={async () => {
                    await axios({
                        method:'PUT',
                        url:'/data/usersheet/'+ userId +'/quitService'
                    }).then(()=>{props.history.goBack()})
                }}>
                    démissions
                </button>
                <button className={'btn --medium'} onClick={()=>{props.history.push('/'+ me.service +'/personnel/personnel')}}>
                    retour
                </button>
                <button className={'btn --medium'} onClick={()=>{setMaterialPopup(true)}}>
                    <img src={'/assets/images/edit.png'} alt={''}/> matériel
                </button>
                <button className={'btn --medium'} onClick={()=>{setSanctionPopup(true)}}>
                    + sanction
                </button>
            </section>
            <section className={'PersoCard'}>
                <div className={'entreprise'}>
                    <h3>entreprise :</h3>
                    <div className={'form-part form-column'}>
                        <label>date d'inscription</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.created_at : '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>Grade LSCoFD</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.get_fire_grade.name : '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>Grade SAMS</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.get_medic_grade.name : '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>matricule</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.matricule : '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>id</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.id : '')}/>
                    </div>
                </div>
                <div className={'perso'}>
                    <h3>personnelle :</h3>
                    <div className={'form-part form-column'}>
                        <label>prénom nom :</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.name : '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>n° de téléphone :</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.tel : '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>n° de compte :</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.compte: '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>lieux de résidence :</label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.liveplace : '')}/>
                    </div>
                    <div className={'form-part form-column'}>
                        <label>discord id : </label>
                        <input type={'text'} disabled={true}  value={(user !== null ? user.discord_id : '')}/>
                    </div>
                </div>
            </section>
            <section className={'material'}>
                <div className={'material-list'}>
                    <div className={'material-item'}>
                        <img src={'/assets/images/material/fire-extinguisher.png'} alt={''}/><p>extincteur</p>
                    </div>
                    <div className={'material-item'}>
                        <img src={'/assets/images/material/flare.png'} alt={''}/> <p>flare</p>
                    </div>
                    <div className={'material-item'}>
                        <img src={'/assets/images/material/flaregun.png'} alt={''}/> <p>flare-gun</p>
                    </div>
                    <div className={'material-item'}>
                        <img src={'/assets/images/material/flashlights.png'} alt={''}/> <p>flashlights</p>
                    </div>
                    <div className={'material-item'}>
                        <img src={'/assets/images/material/kevlar.png'} alt={''}/> <p>kevlar</p>
                    </div>
                                    </div>
            </section>
        </section>
        <section className={'center-part ' + (sanctionPopup ? 'popupBg':'') + (materialPopup ? 'popupBg':'') }>
            <CardComponent title={'sanctions'}>
                <div className={'sanctions-list'}>
                    {user && user.sanctions !== null && user.sanctions.map((e)=>
                        <div className={'sanctions-item'}>
                            <p><label>type : </label> {e.type}</p>
                            <p><label>date : </label> {e.prononcedam}</p>
                            {e.type === 'mise à pied' &&
                                <p><label>durée : </label>{e.duration}</p>
                            }
                            <p><label>raison : </label> {e.reason}</p>
                            <p><label>personnel : </label> {e.prononcedby}</p>
                        </div>
                    )}
                </div>
            </CardComponent>
        </section>
        <section className={'right-part ' + (sanctionPopup ? 'popupBg':'') + (materialPopup ? 'popupBg':'')}>
            <CardComponent title={'notes'}>
                <div className={'notes-list'}>
                    {user && user.note !== null && user.note.map((e)=>

                        <div className={'note-item'} key={e.id}>
                            <p><label>date : </label> {e.posted_at}</p>
                            <p><label>note : </label> {e.note}</p>
                            <p><label>personnel : </label> {e.sender}</p>
                        </div>

                    )}
                </div>
                <div className={'notes-form'}>
                    <textarea placeholder={'écrire une note'} value={note} onChange={(e)=>setNote(e.target.value)}/>
                    <div className={'form-part form-inline'}>
                        <p>{me ? me.service : '?'}</p> <button className={'btn'} onClick={ async () => {
                            await axios({
                                method: 'POST',
                                url: '/data/usersheet/' + userId + '/note',
                                data: {
                                    note,
                                }
                            }).then(r => {update(); setNote('')})
                    }}>ajouter</button>
                    </div>
                </div>
            </CardComponent>
        </section>

        {sanctionPopup &&
            <section className={'popup'}>
                <CardComponent title={'ajouter une sanction'}>
                    <div className={'form-part form-column'}>
                        <select onChange={(e) => {
                            setSanction(e.target.value)
                        }} value={sanction}>
                            <option value={0} disabled={true}>choisir</option>
                            <option value={1}>Avertissement</option>
                            <option value={2}>Mise à pied</option>
                            <option value={3}>Dégrader</option>
                            <option value={4}>Exclure</option>
                        </select>
                    </div>
                    {sanction === "4" &&
                        <div className={'form-part form-column'}>
                            <label>note du licenciement :</label>
                            <textarea placeholder={'(sans préavis, ni indemnité de licenciement, ni prime, ni salaire)'}
                                      value={note_lic} onChange={(e) => {
                                setnote_lic(e.target.value)
                            }}/>
                        </div>
                    }

                    {sanction === "2" &&
                        <div className={'form-part form-column'}>
                            <label>Jusqu'au :</label>
                            <input type={"text"} placeholder={'XXj XXh'} value={MAP} onChange={(e) => {
                                setMAP(e.target.value)
                            }}/>
                        </div>
                    }
                    <div className={'form-part form-column'}>
                        <label>Raison</label>
                        <textarea value={reason} onChange={(e) => {
                            setReason(e.target.value)
                        }}/>
                    </div>

                    <div className={'form-part form-line'}>
                        <button className={'btn'} onClick={() => {
                            setSanctionPopup(false)
                        }}>fermer
                        </button>
                        <button className={'btn'} onClick={async () => {
                            await axios({
                                method: 'post',
                                url: '/data/usersheet/' + userId + '/sanctions',
                                data: {
                                    sanction,
                                    infos: {
                                        'map_date': MAP,
                                        note_lic,
                                        reason
                                    }
                                },
                            }).then(r => {
                                setnote_lic('')
                                setSanctionPopup(false);
                                setSanction(0)
                                setReason('')
                                setMAP('')
                            })
                        }}> envoyer
                        < /button>
                    </div>

                </CardComponent>
            </section>
        }

        {materialPopup &&
            <section className={'popup'}>
                <CardComponent title={'ajouter une sanction'}>
                    <div className={'form-part form-inline'}>
                        <input type={'text'} disabled={true} value={'extincteur'}/>
                        <button className={'btn'} onClick={()=>{
                            let FinalMatList = {
                                'extincteur':!material.extincteur,
                                'flashlight':material.flashlight,
                                'flare':material.flare,
                                'kevlar':material.kevlar,
                                'flaregun':material.flaregun,
                            }
                            setMaterial(FinalMatList);
                        }}><img src={'/assets/images/'+ (material ?( material.extincteur ? 'accept' : 'decline' ) : 'decline') +'.png'} alt={''}/></button>
                    </div>
                    <div className={'form-part form-inline'}>
                        <input type={'text'} disabled={true} value={'flashlight'}/>
                        <button className={'btn'} onClick={()=>{
                            let FinalMatList = {
                                'extincteur':material.extincteur,
                                'flashlight':!material.flashlight,
                                'flare':material.flare,
                                'kevlar':material.kevlar,
                                'flaregun':material.flaregun,
                            }
                            setMaterial(FinalMatList);
                        }}><img src={'/assets/images/'+ (material ?( material.flashlight ? 'accept' : 'decline' ) : 'decline') +'.png'} alt={''}/></button>
                    </div>
                    <div className={'form-part form-inline'}>
                        <input type={'text'} disabled={true} value={'flare'}/>
                        <button className={'btn'} onClick={()=>{
                            let FinalMatList = {
                                'extincteur':material.extincteur,
                                'flashlight':material.flashlight,
                                'flare':!material.flare,
                                'kevlar':material.kevlar,
                                'flaregun':material.flaregun,
                            }
                            setMaterial(FinalMatList);
                        }}><img src={'/assets/images/'+ (material ?( material.flare ? 'accept' : 'decline' ) : 'decline') +'.png'} alt={''}/></button>
                    </div>
                    <div className={'form-part form-inline'}>
                        <input type={'text'} disabled={true} value={'kevlar'}/>
                        <button className={'btn'} onClick={()=>{
                            let FinalMatList = {
                                'extincteur':material.extincteur,
                                'flashlight':material.flashlight,
                                'flare':material.flare,
                                'kevlar':!material.kevlar,
                                'flaregun':material.flaregun,
                            }
                            setMaterial(FinalMatList);
                        }}><img src={'/assets/images/'+ (material ?( material.kevlar ? 'accept' : 'decline' ) : 'decline') +'.png'} alt={''}/></button>
                    </div>
                    <div className={'form-part form-inline'}>
                        <input type={'text'} disabled={true} value={'flaregun'}/>
                        <button className={'btn'} onClick={()=>{
                            let FinalMatList = {
                                'extincteur':material.extincteur,
                                'flashlight':material.flashlight,
                                'flare':material.flare,
                                'kevlar':material.kevlar,
                                'flaregun':!material.flaregun,
                            }
                            setMaterial(FinalMatList);
                        }}><img src={'/assets/images/'+ (material ?( material.flaregun ? 'accept' : 'decline' ) : 'decline') +'.png'} alt={''}/></button>
                    </div>

                    <div className={'form-part form-line'}>
                        <button className={'btn'} onClick={()=>{setMaterialPopup(false)}}>fermer</button>
                        <button className={'btn'} onClick={async () => {
                            await axios({
                                method: 'PUT',
                                url: '/data/usersheet/'+ userId +'/material',
                                data:{
                                    material
                                }
                            }).then(r=>{
                                setMaterialPopup(false)
                            })
                        }
                        }>envoyer</button>
                    </div>

                </CardComponent>
            </section>
        }


    </div> )
}

export default FichePersonnel;
