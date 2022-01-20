import React, {useState} from 'react';
import SwitchBtn from "../../../props/SwitchBtn";
import CardComponent from "../../../props/CardComponent";
import PatientList from "./PatientList";

function MedicBC(props) {

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
                        <label>Type de Black Code</label>
                        <input type={'text'}/>
                    </div>
                    <div className={'form-group form-column'}>
                        <label>Caserne envoyé</label>
                        <input type={'text'}/>
                    </div>
                </div>
                <div className={'BC-personnel'}>
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
                    <div className={'form-group form-column'}>
                        <label>Couleur vètement</label>
                        <select>
                            <option>test 1</option>
                            <option>test 2</option>
                        </select>
                    </div>
                    <div className={'form-group form-line'}>
                        <label>Carte Id : </label>
                        <SwitchBtn number={'A0'} checked={payed} callback={()=>{setPayed(!payed)}}/>
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
            <PatientList/>
        </section>
    </div>  )
}

export default MedicBC;
