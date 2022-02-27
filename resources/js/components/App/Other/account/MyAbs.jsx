import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import UserContext from "../../../context/UserContext";


function MyAbs(props) {
    const [reason, setReason] = useState('');
    const [start, setStart] = useState('');
    const [end, setEnd] = useState('');
    const user = useContext(UserContext)
    const [errors, setErrors] = useState([]);
    const [absList, setAbsList] = useState([])

    useEffect(()=>{
        getMyAbs();

    }, [])

    const getMyAbs = async () =>{
        await axios({
            method: 'GET',
            url: '/data/absence',
        }).then(r=>{
            setAbsList(r.data.abs)
        });
    }

    const postReq = async () => {
        await  axios({
            method: 'POST',
            url: '/data/absence',
            data:{
                reason,
                'start_at': start,
                'end_at': end,
            }
        }).then(r => {
            getMyAbs()
            setStart('')
            setEnd('')
            setReason('')
            setErrors([])
        }).catch(error => {
            if(error.response.status === 422){
                setErrors(error.response.data.errors)
            }
        })
    }

    return (
        <section className={'PageDisplayed MyAbs'}>
            <section className={'Form'}>
                <div className={'form-part form-inline'}>
                    <label>Service</label>
                    <label>{user.service}</label>
                </div>

                <div className={'form-part form-column'}>
                    <label>Raison</label>
                    <input type={'text'} value={reason} className={(errors.reason ? 'form-error': '')} onChange={(e)=>{setReason(e.target.value)}}/>
                </div>
                <ul className={'error-list'}>
                    {errors.reason && errors.reason.map((item)=>
                        <li>{item}</li>
                    )}
                </ul>

                <div className={'form-part form-column'}>
                    <label>début</label>
                    <input type={'date'} value={start} className={(errors.start_at ? 'form-error': '')} onChange={(e)=>{setStart(e.target.value)}}/>
                </div>
                <ul className={'error-list'}>
                    {errors.start_at && errors.start_at.map((item)=>
                        <li>{item}</li>
                    )}
                </ul>


                <div className={'form-part form-column'}>
                    <label>fin</label>
                    <input type={'date'} value={end} className={(errors.end_at ? 'form-error': '')} onChange={(e)=>{setEnd(e.target.value)}}/>
                </div>
                <ul className={'error-list'}>
                    {errors.end_at && errors.end_at.map((item)=>
                        <li>{item}</li>
                    )}
                </ul>

                <div className={'form-part form-inline'}>
                    <button className={'btn'} disabled={!(user.grade.admin || user.post_absences_req)} onClick={postReq}>envoyer</button>
                </div>
            </section>
            <section className={'table'}>
                <section className={'table-scroller'}>
                    <table>
                        <thead>
                        <tr>
                            <th>début</th>
                            <th>fin</th>
                            <th>état</th>
                        </tr>
                        </thead>
                        <tbody>
                        {absList && absList.map((p)=>
                            <tr key={p.id}>
                                <td>{p.start_at}</td>
                                <td>{p.end_at}</td>
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

export default MyAbs;
