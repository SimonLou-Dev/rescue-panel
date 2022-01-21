import React, {useState} from 'react';
import {Link, NavLink} from "react-router-dom";
import axios from "axios";
import SwitchBtn from "../props/SwitchBtn";


const GetInfos = (props) => {
    const [livingplace, setlivingplace] = useState(1);
    const [name, setname] = useState('');
    const [tel, settlel] = useState('');
    const [staff, setStaff] = useState(false);
    const [compte, setcompte]= useState('');
    const [errors, seterrors]= useState([]);
    const [service, setService]= useState('aucun');

    const sendinfos = async (e) => {
        e.preventDefault();
        await axios({
            method: 'post',
            url: '/data/postuserinfos',
            data: {
                'living': livingplace,
                'name':name,
                'tel': tel,
                'compte': compte,
                'staff':staff,
                'service':service,
                'X-CSRF-TOKEN': csrf,
            }
        }).then(response => {
            if (response.status === 201) {
                if(response.data.accessRight){
                    props.history.push('/dashboard');
                }else{
                    props.history.push("/cantaccess");
                }

            }
        }).catch(error => {
            error = Object.assign({}, error);
            if(error.response.status === 422){
                seterrors(error.response.data.errors)
            }
        })
    }

    return (
        <div className={'Auth'}>
            <div className={'Authentifier'}>
                <form method={'POST'} onSubmit={sendinfos}>
                    <div className={'auth-header'}>
                        <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    </div>
                    <div className={'auth-content'}>
                        <h1>Informations</h1>

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
                            <label>Staff </label>
                            <SwitchBtn number={'A0'} value={staff} callback={()=>{setStaff(!staff)}}/>
                        </div>

                        <div className={'form-part form-line'}>
                            <label>Service </label>
                            <select value={service} onChange={(e)=>{setService(e.target.value)}}>
                                <option>aucun</option>
                                <option>LSCoFD</option>
                                <option>OMC</option>
                            </select>
                            <ul className={'error-list'}>
                                {errors.compte && errors.compte.map((item)=>
                                    <li>{item}</li>
                                )}
                            </ul>
                        </div>
                    </div>
                    <div className={'auth-footer'}>
                        <button type={'submit'} className={'btn --medium'}>Terminer</button>
                    </div>
                </form>
            </div>
        </div>

    )

}

export default GetInfos;
