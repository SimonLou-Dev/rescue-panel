import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import UserContext from "../../../context/UserContext";
import {set} from "lodash/object";


function MyPrimes(props) {
    const [modifierValue, setModifierValue] = useState('');
    const [modifierReason, setModifierReason] = useState('');
    const user = useContext(UserContext)
    const [errors, setErrors] = useState([]);
    const [PrimesList, setPrimesList] = useState([])

    useEffect(()=>{
        getMyPrimes();

    }, [])

    const getMyPrimes = async () =>{
        await axios({
            method: 'GET',
            url: '/data/primes/getmy',
        }).then(r=>{
           setPrimesList(r.data.primes);
        });
    }

    const postReq = async () => {
        await  axios({
            method: 'POST',
            url: '/data/primes/post',
            data:{
                'reason': modifierReason,
                'montant': modifierValue,
            }
        }).then(r => {
            getMyPrimes()
            setModifierValue('')
            setModifierReason('')
        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })
    }


    return (
        <section className={'PageDisplayed MyPrimes'}>
            <section className={'Form'}>
                <div className={'form-part form-inline'}>
                    <label>Service</label>
                    <label>{user.service}</label>
                </div>

                <div className={'form-part form-inline'}>
                    <label>montant </label>
                    <input type={'number'} placeholder={'montant en $'} value={modifierValue} className={(errors.montant ? 'form-error': '')} onChange={(e)=>{setModifierValue(e.target.value); }}/>
                </div>
                <ul className={'error-list'}>
                    {errors.montant && errors.montant.map((item)=>
                        <li>{item}</li>
                    )}
                </ul>
                <div className={'form-part form-inline'}>
                    <label>raison </label>
                    <input type={'text'} maxLength={25} value={modifierReason} className={(errors.tel ? 'form-error': '')} onChange={(e)=>{setModifierReason(e.target.value); }}/>
                </div>
                <ul className={'error-list'}>
                    {errors.reason && errors.reason.map((item)=>
                        <li>{item}</li>
                    )}
                </ul>
                <div className={'form-part form-inline'}>
                    <button className={'btn'} onClick={postReq}>envoyer</button>
                </div>
            </section>
            <section className={'table'}>
                <section className={'table-scroller'}>
                    <table>
                        <thead>
                            <tr>
                                <th>semaine</th>
                                <th>raison</th>
                                <th>montant</th>
                                <th>état</th>
                            </tr>
                        </thead>
                        <tbody>
                        {PrimesList && PrimesList.map((p)=>
                            <tr key={p.id}>
                                <td>{p.week_number}</td>
                                <td>{p.reason}</td>
                                <td>{p.montant}</td>
                                <td>{p.accepted === null ? 'en cours' : (p.accepted ? 'accepté' : 'refusée')}</td>
                            </tr>
                        )}
                        </tbody>

                    </table>
                </section>
            </section>
        </section>
    )
}

export default MyPrimes;
