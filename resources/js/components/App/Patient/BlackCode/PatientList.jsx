import React from 'react';
import CardComponent from "../../../props/CardComponent";

function PatientList(props) {

    return (<div className={'PatientList'}>
        <CardComponent title={'Liste de patient (1)'}>
            <div className={'patient-listing'}>
                <table>
                    <tbody>
                    <tr>
                        <td className={'id'}>[ID]</td>
                        <td className={'name'}>Jean Claude</td>
                        <td className={'date'}>14:01</td>
                        <td className={'color'}>Rouge</td>
                        <td className={'action'}><button className={'btn'}><img alt={''} src={'/assets/images/documents.png'}/></button> <button className={'btn'}><img alt={''} src={'/assets/images/decline.png'}/></button></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </CardComponent>
    </div> )
}

export default PatientList;
