import React, {useState} from 'react';
import PatientList from "./PatientList";
import SwitchBtn from "../../../props/SwitchBtn";
import {set} from "lodash/object";
import CardComponent from "../../../props/CardComponent";

function FireBC(props) {
    const [service, setService] = useState('LScoFD');
    const [payed, setPayed] = useState(false);
    const [id, setId] = useState(false);

    const Redirection = (url) => {
        props.history.push(url)
    }


    return (<div className={'BC-View'}>
        <section className={'BC-Header'}>
            <div className={'BC-Place'}>
                <h5>Paletto Route</h5>
            </div>
            <div className={'BC-Starter'}>
                <h5>Seamus Valentine</h5><img alt={''} src={'/assets/images/LSCoFD.png'}/>
            </div>
            <div className={'BC-Commands'}>
                <button  className={'btn'} onClick={()=>{Redirection('/blackcodes/all')}}>retour</button>
                <button  className={'btn'}>terminer</button>
                <button  className={'btn'}><img alt={''} src={'/assets/images/pdf.png'}/></button>
            </div>
        </section>
        <section className={'BC-Content'}>
            <section className={'BC-infos'}>
                <div className={'BC-infosForm'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Information</label>
                        <button className={'btn img'}><img src={'/assets/images/save.png'} alt={''}/></button>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Type d'incendie</label>
                        <input type={'text'}/>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Lieux</label>
                        <input type={'text'}/>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Déclenchement</label>
                        <div className={'form-group form-line'}>
                            <input type={'date'}/>
                            <input type={'time'}/>
                        </div>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Caserne envoyé</label>
                        <input type={'text'}/>
                    </div>
                </div>
                <div className={'BC-personnel'}>
                    <div className={'personnel-adder form-line form-group'}>
                        <input type={"text"} placeholder={'prénom nom'}/>
                        <button className={'btn'}>ajouter</button>
                    </div>
                    <ul className={'Personnel-list'}>

                        <li className={'personnel-tag'}>
                            <h6>Seamus Valentine </h6> <img alt={''} src={'/assets/images/LSCoFD.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Rick O'Shea </h6>  <img alt={''} src={'/assets/images/OMC.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Seamus Valentine </h6> <img alt={''} src={'/assets/images/LSCoFD.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Seamus Valentine </h6> <img alt={''} src={'/assets/images/LSCoFD.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Rick O'Shea </h6>  <img alt={''} src={'/assets/images/OMC.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Seamus Valentine </h6> <img alt={''} src={'/assets/images/LSCoFD.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Rick O'Shea </h6>  <img alt={''} src={'/assets/images/OMC.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Seamus Valentine </h6> <img alt={''} src={'/assets/images/LSCoFD.png'}/>
                        </li>
                        <li className={'personnel-tag'}>
                            <h6>Rick O'Shea </h6>  <img alt={''} src={'/assets/images/OMC.png'}/>
                        </li>
                    </ul>
                </div>
            </section>
            <section className={'BC-Patient'}>
                <div className={'BC-PatientAdder'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Ajouter un patient</label>
                        <button className={'btn'}>ajouter</button>
                        <button className={'btn'}>effacer</button>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>prénom nom</label>
                        <input type={'text'}/>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Type de blessure</label>
                        <select>
                            <option>test 1</option>
                            <option>test 2</option>
                        </select>
                    </div>
                    <div className={'form-group form-line'}>
                        <label>Payé : </label>
                        <SwitchBtn number={'A0'} checked={payed} callback={()=>{setPayed(!payed)}}/>
                    </div>
                </div>
                <div className={'BC-InetDetails'}>
                    <div className={'form-group form-line form-title'}>
                        <label>Détails de l'intervetion</label>
                        <button className={'btn img'}><img src={'/assets/images/save.png'} alt={''}/></button>
                    </div>
                    <textarea />
                </div>

            </section>
            <div className={'patientList'}>
                <CardComponent title={'Liste de patient (1)'}>
                    <div className={'patient-listing'}>
                        <table>
                            <tbody>
                            <tr>
                                <td className={'name'}>Jean Claude</td>
                                <td className={'date'}>14:01</td>
                                <td className={'action'}><button className={'btn'}><img alt={''} src={'/assets/images/documents.png'}/></button> <button className={'btn'}><img alt={''} src={'/assets/images/decline.png'}/></button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </CardComponent>
            </div>
        </section>
    </div> )
}

export default FireBC;
