import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import SwitchBtn from "../../../props/SwitchBtn";
import UserContext from "../../../context/UserContext";


function MyInfo(props) {
    const user = useContext(UserContext)
    const [livingplace, setlivingplace] = useState(user.liveplace);
    const [name, setname] = useState(user.name);
    const [tel, settlel] = useState(user.tel);
    const [matricule, setMatricule] = useState(user.matricule);
    const [compte, setcompte]= useState(user.compte);
    const [errors, seterrors]= useState([]);


    const postInfos = async () => {

        await axios({
            method: 'PUT',
            url: '/data/user/infos/put',
            data:{
                name,
                compte,
                tel,
                matricule,
                'liveplace':livingplace,
            }
        }).then(r=>{

        }).catch(error => {
            if(error.response.status === 422){
                seterrors(error.response.data.errors)
            }
        })
    }

    useEffect(()=>{
        setTimeout(function (){
            setlivingplace(user.liveplace);
            setname(user.name);
            settlel(user.tel)
            setMatricule(user.matricule)
            setcompte(user.compte)
        }, 200)

    }, [])


    return (
        <section className={'PageDisplayed Infos'}>
            <section className={'modifier'}>
                <div className={'form-part form-column'}>
                    <label>prénom nom RP</label>
                    <input type={'text'} value={name} className={(errors.name ? 'form-error': '')} onChange={(e)=>{setname(e.target.value)}}/>
                    <ul className={'error-list'}>
                        {errors.name && errors.name.map((item)=>
                            <li>{item}</li>
                        )}
                    </ul>
                </div>

                <div className={'form-part form-column'}>
                    <label>matricule</label>
                    <input type={'text'} value={matricule} className={(errors.matricule ? 'form-error': '')} onChange={(e)=>{setMatricule(e.target.value)}}/>
                    <ul className={'error-list'}>
                        {errors.matricule && errors.matricule.map((item)=>
                            <li>{item}</li>
                        )}
                    </ul>
                </div>

                {(user.fire || (user.medic && user.crossService)) &&
                    <div className={'form-part form-column'}>
                        <label>Grade LSCoFD {user.service === 'LSCoFD' ? '(Service actuel)' : ''}</label>
                        <input type={'text'} value={user.fire_grade_name} disabled={true}/>
                    </div>
                }

                {(user.medic || (user.fire && user.crossService)) &&
                    <div className={'form-part form-column'}>
                        <label>Grade SAMS {user.service === 'SAMS' ? '(Service actuel)' : ''}</label>
                        <input type={'text'} value={user.medic_grade_name} disabled={true}/>
                    </div>
                }

                <div className={'form-part form-column'}>
                    <label>Comté habité : </label>
                    <select defaultValue={livingplace} onChange={(e)=>setlivingplace(e.target.value) }>
                        <option value={1} disabled>choisir</option>
                        <option>LS</option>
                        <option>BC</option>
                    </select>
                </div>

                <div className={'form-part form-column'}>
                    <label>n° de tel IG : </label>
                    <input type={'text'} placeholder={'555-xxxxx'} value={tel} className={(errors.tel ? 'form-error': '')} onChange={(e)=>{settlel(e.target.value); }}/>
                    <ul className={'error-list'}>
                        {errors.tel && errors.tel.map((item)=>
                            <li>{item}</li>
                        )}
                    </ul>
                </div>

                <div className={'form-part form-column'}>
                    <label>n° de compte </label>
                    <input type={'number'} value={compte} className={(errors.compte ? 'form-error': '')} name={'psw'} onChange={(e)=>{setcompte(e.target.value);}}/>
                    <ul className={'error-list'}>
                        {errors.compte && errors.compte.map((item)=>
                            <li>{item}</li>
                        )}
                    </ul>
                </div>

                <div className={'form-part form-line'}>
                   <button className={'btn'} onClick={postInfos}>envoyer</button>
                </div>
            </section>
            <section className={'other'}>
                <section className={'sanctions'}>
                    <h1>sanctions</h1>
                    <ul>
                        {user.sanctions !== null && user.sanctions.length !== 0 && user.sanctions.map((s)=>
                            <li className={'sanctionTag'} key={s.prononcedam} >
                                {s.prononcedam} - {s.type} : {s.reason}
                            </li>
                        )}

                    </ul>
                </section>
                <section className={'materiel'}>
                    <h1>Matériel</h1>
                    <ul>
                        {user.length != 0 && user.materiel != null && user.materiel.extincteur &&
                            <div className={'material-item'}>
                                <img src={'/assets/images/material/fire-extinguisher.png'} alt={''}/><p>extincteur</p>
                            </div>
                        }
                        {user.length != 0 && user.materiel != null && user.materiel.flare &&
                            <div className={'material-item'}>
                                <img src={'/assets/images/material/flaregun.png'} alt={''}/> <p>flare</p>
                            </div>
                        }
                        {user.length != 0 && user.materiel != null && user.materiel.flaregun &&
                            <div className={'material-item'}>
                                <img src={'/assets/images/material/flaregun.png'} alt={''}/> <p>flare-gun</p>
                            </div>
                        }
                        {user.length != 0 && user.materiel != null && user.materiel.flashlight &&
                            <div className={'material-item'}>
                                <img src={'/assets/images/material/flashlights.png'} alt={''}/> <p>flashlights</p>
                            </div>
                        }
                        {user.length != 0 && user.materiel != null && user.materiel.kevlar &&
                            <div className={'material-item'}>
                                <img src={'/assets/images/material/kevlar.png'} alt={''}/> <p>kevlar</p>
                            </div>
                        }
                    </ul>

                </section>
            </section>

        </section>
    )
}

export default MyInfo;
