import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import UserContext from "../../../context/UserContext";


function MyRemboursements(props) {
    const [remboursementReason, setRemboursementReason] = useState('');
    const [remboursementMontant, setRemboursementMontant] = useState('');
    const user = useContext(UserContext)
    const [errors, setErrors] = useState([]);
    const [remboursementList, setRemboursementList] = useState([])

    useEffect(()=>{
        getMyRemboursements();

    }, [])

    const getMyRemboursements = async () =>{
        await axios({
            method: 'GET',
            url: '/data/remboursements/get',
        }).then(r=>{
            setRemboursementList(r.data.remboursements);
        });
    }

    const postReq = async () => {
        await  axios({
            method: 'POST',
            url: '/data/remboursements/post',
            data:{
                'reason': remboursementReason,
                'montant': remboursementMontant,
            }
        }).then(r => {
            getMyRemboursements()
            setRemboursementReason(0)
        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })
    }


    return (
        <section className={'PageDisplayed MyRemboursements'}>
            <section className={'Form'}>
                <div className={'form-part form-inline'}>
                    <label>Service</label>
                    <label>{user.service}</label>
                </div>

                <div className={'form-part form-inline'}>
                    <label>raison </label>
                    <input type={'text'} value={remboursementReason} className={(errors.tel ? 'form-error': '')} onChange={(e)=>{setRemboursementReason(e.target.value); }}/>
                </div>
                <div className={'form-part form-inline'}>
                    <label>montant </label>
                    <input type={'text'} value={remboursementMontant} className={(errors.tel ? 'form-error': '')} onChange={(e)=>{setRemboursementMontant(e.target.value); }}/>
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
                        {remboursementList && remboursementList.map((p)=>
                            <tr key={p.id}>
                                <td>{p.week_number}</td>
                                <td>{p.reason} </td>
                                <td>${p.montant}</td>
                                <td>{p.accepted === null ? 'en cours' : (p.accepted ? 'accepté' : 'refusé')}</td>
                            </tr>
                        )}
                        </tbody>

                    </table>
                </section>
            </section>
        </section>
    )
}

export default MyRemboursements;
