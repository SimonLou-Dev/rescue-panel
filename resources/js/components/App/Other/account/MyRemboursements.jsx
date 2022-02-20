import React, {useContext, useEffect, useState} from 'react';
import axios from "axios";
import UserContext from "../../../context/UserContext";


function MyRemboursements(props) {
    const [remboursementReason, setRemboursementReason] = useState(0);
    const [remboursementItem, setRemboursementItem] = useState([]);
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
            setRemboursementItem(r.data.obj);
        });
    }

    const postReq = async () => {
        await  axios({
            method: 'POST',
            url: '/data/remboursements/post',
            data:{
                'item': remboursementReason,
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
                    <select value={remboursementReason} className={(errors.tel ? 'form-error': '')} onChange={(e)=>{setRemboursementReason(e.target.value); }}>
                        <option value={0} disabled={true}>choisir</option>
                        {remboursementItem.map((i)=>
                            <option key={i.id} value={i.id}>{i.name}</option>
                        )}
                    </select>
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
                        </tr>
                        </thead>
                        <tbody>
                        {remboursementList && remboursementList.map((p)=>
                            <tr key={p.id}>
                                <td>{p.week_number}</td>
                                <td>{p.get_item.name}</td>
                                <td>{p.total}</td>
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
