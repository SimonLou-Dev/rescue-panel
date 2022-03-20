import React, {useEffect} from 'react';
import CardComponent from "../../../props/CardComponent";
import axios from "axios";

function PatientList(props) {

    const Redirection = (url) => {
        props.history.push(url)
    }

    return (<div className={'PatientList'}>
        <CardComponent title={'Liste de patient (' + (props.list ? props.list.length : 0) + ')'}>
            <div className={'patient-listing'}>
                <table>
                    <tbody>
                    {props.list && props.list.map((p) =>
                        <tr key={p.id}>
                            <td className={'id'}>{p.idcard ? '[ID]' : ''}</td>
                            <td className={'name clickable'} onClick={()=>{Redirection('/patients/'+p.patient_id+'/view')}}>{p.name}</td>
                            <td className={'date'}>{p.created_at}</td>
                            <td className={'color'}>{p.get_color.name}</td>
                            <td className={'action'}><button className={'btn'} onClick={()=>{Redirection('/patients/' + p.patient_id + '/view?id='+p.rapport_id)}}><img alt={''} src={'/assets/images/documents.png'}/></button> <button className={'btn'} onClick={()=>{
                                axios({
                                    method:  'DELETE',
                                    url : '/data/blackcode/delete/patient/'+p.id
                                })
                            }}><img alt={''} src={'/assets/images/decline.png'}/></button></td>
                        </tr>
                    )}

                    </tbody>
                </table>
            </div>
        </CardComponent>
    </div> )
}

export default PatientList;
