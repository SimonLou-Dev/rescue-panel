import React from 'react';
import PageNavigator from "../../props/PageNavigator";
import CardComponent from "../../props/CardComponent";
import SwitchBtn from "../../props/SwitchBtn";

function grade(props) {

    return (<div className={'GradeList'}>
        <section className={'grades-selector'}>
            <div className={'grade-list'}>
                <table>
                    <thead>
                        <tr>
                            <th className={'grade'}>Grade</th>
                            <th className={'power'}>puissance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td className={'grade'}>Battalion CHief mesl</td>
                            <td className={'power'}>12</td>
                        </tr>
                        <tr>
                            <td className={'grade'}>ajouter</td>
                            <td className={'power'}>12</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section className={'bigpart'}>
            <div className={'bigbordel'}>
                <div className={'header'}>
                    <input type={'text'} placeholder={'nom du grade'}/>
                    <div className={'form-part form-inline'}>
                        <label>puissance : </label>
                        <input type={'number'}/>
                    </div>
                    <button className={'btn'}><img alt={''} src={'/assets/images/save.png'}/></button>
                    <button className={'btn'}>supprimer</button>
                </div>
                <div className={'perm-editor'}>
                    <div className={'perm-line'}>
                        <h4>général</h4>

                        <table>
                            <thead>
                            <tr>
                                <th>administrateur (pas besoin de mettre d'autres perm)</th>
                                <th>accès</th>
                                <th>avoir un matricule</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'1'} checked={false}/></td>
                                <td><SwitchBtn number={'2'} checked={false}/></td>
                                <td><SwitchBtn number={'3'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>Rapports</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>créer</th>
                                <th>modifier</th>
                                <th>voir (en service)</th>
                                <th>voir (tout le temp)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'4'} checked={false}/></td>
                                <td><SwitchBtn number={'5'} checked={false}/></td>
                                <td><SwitchBtn number={'6'} checked={false}/></td>
                                <td><SwitchBtn number={'7'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>Test de poudre</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>créer</th>
                                <th>voir (en service)</th>
                                <th>voir (tout le temp)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'8'} checked={false}/></td>
                                <td><SwitchBtn number={'9'} checked={false}/></td>
                                <td><SwitchBtn number={'10'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>Dossiers</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>modifier (infos patient)</th>
                                <th>voir (en service)</th>
                                <th>voir (tout le temp)</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'11'} checked={false}/></td>
                                <td><SwitchBtn number={'12'} checked={false}/></td>
                                <td><SwitchBtn number={'13'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>BC & Fire</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>voir (en service)</th>
                                <th>voir (tout le temp)</th>
                                <th>ouvrir</th>
                                <th>fermer</th>
                                <th>ajouter un patient</th>
                                <th>ajouter du personnel (feux uniquement)</th>
                                <th>acceder aux feux</th>
                                <th>acceder aux medic</th>
                                <th>modifier les infos</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'14'} checked={false}/></td>
                                <td><SwitchBtn number={'15'} checked={false}/></td>
                                <td><SwitchBtn number={'16'} checked={false}/></td>
                                <td><SwitchBtn number={'17'} checked={false}/></td>
                                <td><SwitchBtn number={'18'} checked={false}/></td>
                                <td><SwitchBtn number={'19'} checked={false}/></td>
                                <td><SwitchBtn number={'20'} checked={false}/></td>
                                <td><SwitchBtn number={'21'} checked={false}/></td>
                                <td><SwitchBtn number={'22'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>factures</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>voir (en service)</th>
                                <th>voir (tout le temp)</th>
                                <th>créer une facture</th>
                                <th>exporter une facture</th>
                                <th>payer une facture</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'23'} checked={false}/></td>
                                <td><SwitchBtn number={'24'} checked={false}/></td>
                                <td><SwitchBtn number={'25'} checked={false}/></td>
                                <td><SwitchBtn number={'26'} checked={false}/></td>
                                <td><SwitchBtn number={'27'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>Personnel</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>voir liste du personnel</th>
                                <th>activer le cross service</th>
                                <th>modifier le grade</th>
                                <th>mettre pilote</th>
                                <th>voir la fiche personnel</th>
                                <th>ajouter une note</th>
                                <th>mettre une MAP</th>
                                <th>exlure qqn</th>
                                <th>mettre un avertissement</th>
                                <th>modifier le matériel</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'28'} checked={false}/></td>
                                <td><SwitchBtn number={'29'} checked={false}/></td>
                                <td><SwitchBtn number={'30'} checked={false}/></td>
                                <td><SwitchBtn number={'31'} checked={false}/></td>
                                <td><SwitchBtn number={'32'} checked={false}/></td>
                                <td><SwitchBtn number={'33'} checked={false}/></td>
                                <td><SwitchBtn number={'34'} checked={false}/></td>
                                <td><SwitchBtn number={'35'} checked={false}/></td>
                                <td><SwitchBtn number={'36'} checked={false}/></td>
                                <td><SwitchBtn number={'37'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <div className={'perm-line'}>
                        <h4>demandes de services</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>faire une demande</th>
                                <th>voir mes demandes</th>
                                <th>voir toutes les demandes</th>
                                <th>accepter / refuser une demande</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'38'} checked={false}/></td>
                                <td><SwitchBtn number={'39'} checked={false}/></td>
                                <td><SwitchBtn number={'40'} checked={false}/></td>
                                <td><SwitchBtn number={'41'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>demandes de primes</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>faire une demande</th>
                                <th>voir mes demandes</th>
                                <th>voir toutes les demandes</th>
                                <th>accepter / refuser une demande</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'42'} checked={false}/></td>
                                <td><SwitchBtn number={'43'} checked={false}/></td>
                                <td><SwitchBtn number={'44'} checked={false}/></td>
                                <td><SwitchBtn number={'45'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>demandes d'absences</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>faire une demande</th>
                                <th>voir mes demandes</th>
                                <th>voir toutes les demandes</th>
                                <th>accepter / refuser une demande</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'46'} checked={false}/></td>
                                <td><SwitchBtn number={'47'} checked={false}/></td>
                                <td><SwitchBtn number={'48'} checked={false}/></td>
                                <td><SwitchBtn number={'49'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>service</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>voir le rapport horaire</th>
                                <th>me mettre en service</th>
                                <th>mettre qqn en service</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'50'} checked={false}/></td>
                                <td><SwitchBtn number={'51'} checked={false}/></td>
                                <td><SwitchBtn number={'52'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className={'perm-line'}>
                        <h4>grade</h4>
                        <table>
                            <thead>
                            <tr>
                                <th>voir la liste des grades</th>
                                <th>modifier un grade</th>
                                <th>editer dans la gestion de contenue</th>
                                <th>edit les chann discord</th>
                                <th>voir les logs</th>
                                <th>poster une annonce</th>
                                <th>poster une actualitée</th>
                                <th>editer les infos utiles</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><SwitchBtn number={'53'} checked={false}/></td>
                                <td><SwitchBtn number={'54'} checked={false}/></td>
                                <td><SwitchBtn number={'55'} checked={false}/></td>
                                <td><SwitchBtn number={'56'} checked={false}/></td>
                                <td><SwitchBtn number={'57'} checked={false}/></td>
                                <td><SwitchBtn number={'58'} checked={false}/></td>
                                <td><SwitchBtn number={'59'} checked={false}/></td>
                                <td><SwitchBtn number={'60'} checked={false}/></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </section>



    </div> )
}

export default grade;
