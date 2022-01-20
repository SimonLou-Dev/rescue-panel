import React, {useState} from 'react';
import Searcher from "../../../props/Searcher";
import PageNavigator from "../../../props/PageNavigator";
import {Link} from "react-router-dom";

function FactureList(props) {
    const [popupDisplayed, displayPopup] = useState(false);

    return (<div className={'Factures'}>
        <div className={'FactureCenter'}>
            <div className={'table-header'}>
                <PageNavigator/>
                <Searcher/>
                <div className={'exporter'}>
                    <div className={'exporter-part'}>
                        <label>du</label>
                        <input type={'date'}/>
                    </div>
                    <div className={'exporter-part'}>
                        <label>au</label>
                        <input type={'date'}/>
                    </div>
                    <a href={''} target={'_blank'} className={'bnt'}><img alt={''} src={'/assets/images/xls.png'}/></a>
                </div>
                <button className={'btn'} onClick={()=>{displayPopup(true)}}>ajouter</button>
            </div>
            <div className={'table-container'}>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>date</th>
                            <th>montant</th>
                            <th/>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td className={'link'}><Link to={'/patients/1/view'}>Jean Claude</Link></td>
                            <td>13/01/2022 à 00h10</td>
                            <td>$400</td>
                            <td><button className={'btn'}>payer</button></td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td className={'link'}><Link to={'/patients/1/view'}>Jean Claude</Link></td>
                            <td>13/01/2022 à 00h10</td>
                            <td>$400</td>
                            <td><button className={'btn'}>payer</button></td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td className={'link'}><Link to={'/patients/1/view'}>Jean Claude</Link></td>
                            <td>13/01/2022 à 00h10</td>
                            <td>$400</td>
                            <td><button className={'btn'}>payer</button></td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td className={'link'}><Link to={'/patients/1/view'}>Jean Claude</Link></td>
                            <td>13/01/2022 à 00h10</td>
                            <td>$400</td>
                            <td><button className={'btn'}>payer</button></td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td className={'link'}><Link to={'/patients/1/view'}>Jean Claude</Link></td>
                            <td>13/01/2022 à 00h10</td>
                            <td>$400</td>
                            <td><button className={'btn'}>payer</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div> )
}

export default FactureList;
