import React, {useState} from 'react';
import {Link} from "react-router-dom";
import axios from "axios";


const GetInfos = (props) => {
    const [livingplace, setlivingplace] = useState(1);
    const [name, setname] = useState('');
    const [tel, settlel] = useState('');
    const [compte, setcompte]= useState('');
    const [errors, seterrors]= useState([]);

    const sendinofs = async (e) => {
        e.preventDefault();
        await axios({
            method: 'post',
            url: '/data/postuserinfos',
            data: {
                'living': livingplace,
                'name':name,
                'tel': tel,
                'compte': compte,
                'X-CSRF-TOKEN': csrf,
            }
        }).then(response => {
            if (response.status === 201) {
                if(response.data.accessRight){
                    window.location.href = "/";
                }else{
                    window.location.href = "/cantaccess";
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
        <div className={'Register'}>
            <div className={'Form'}>
                <form method={'POST'} onSubmit={this.sendinfos}>
                    <img alt={""} src={'/assets/images/LONG_EMS_BC_2.png'}/>
                    <h1>Informations</h1>
                    <label>Contré habité : </label>
                    <select defaultValue={livingplace} onChange={(e)=>setlivingplace(e.target.value) }>
                        <option value={1} disabled>choisir</option>
                        <option>LS</option>
                        <option>BC</option>
                    </select>

                    <input type={'text'} value={name} className={(errors.name ? 'form-error': '')} onChange={(e)=>{setname(e.target.value)}}/>
                    <ul className={'error-list'}>
                        {errors.name && errors.name.map((item)=>
                            <li>{item}</li>
                        )}
                    </ul>

                    <label>n° de tel IG : </label>
                    <input type={'number'} value={tel} className={(errors.tel ? 'form-error': '')} onChange={(e)=>{settlel(e.target.value); }}/>
                    <ul className={'error-list'}>
                        {errors.tel && errors.tel.map((item)=>
                            <li>{item}</li>
                        )}
                    </ul>
                    <label>n° de compte </label>
                    <input type={'number'} value={compte} className={(errors.compte ? 'form-error': '')} name={'psw'} onChange={(e)=>{setcompte(e.target.value);}}/>
                    <ul className={'error-list'}>
                        {errors.compte && errors.compte.map((item)=>
                            <li>{item}</li>
                        )}
                    </ul>
                    <div className={'btn-contain'}>
                        <button type={'submit'} className={'btn'}>Terminer</button>
                    </div>
                </form>
            </div>
        </div>

    )

}

export default GetInfos;
