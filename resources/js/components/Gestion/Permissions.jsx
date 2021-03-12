import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import {Link} from "react-router-dom";
import TableBottom from "../props/utils/TableBottom";




class Permissions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            obj: [{id:1}, {id:2}, {id:3}, {id:4}, {id:5}, {id:6}, {id:7}, {id:8}, {id:9}, {id:10}, {id:11}, {id:12}]
        }

    }

    render() {
        return (
            <div class={"perm"}>
                <div className="header">
                    <PagesTitle title={'Gestion des permissions'}/>
                    <Link to={'/gestion/personnel'} className={'btn'}>Retour</Link>
                </div>
                <div className="content">
                    <div className="tablecontainer">
                        <table>
                            <thead>
                                <tr>
                                    <th className={'p-grade p-head'}>grade</th>
                                    <th className={'p-perm p-head'}>acceder au site</th>
                                    <th className={'p-perm p-head'}>faire des rapports hors service</th>
                                    <th className={'p-perm p-head'}>acceder au dossier hors service</th>
                                    <th className={'p-perm p-head'}>lancer un BC hors service</th>
                                    <th className={'p-perm p-head'}>sortir le pdf des facture</th>
                                    <th className={'p-perm p-head'}>ajouter des factures</th>
                                    <th className={'p-perm p-head'}>créer un rapport</th>
                                    <th className={'p-perm p-head'}>créer un BC</th>
                                    <th className={'p-perm p-head'}>acceder au récapitulatif des remboursement</th>
                                    <th className={'p-perm p-head'}>éditer les informations</th>
                                    <th className={'p-perm p-head'}>acceder au carnet de vol (sans être pilote)</th>
                                    <th className={'p-perm p-head'}>accerder au rapport horaire</th>
                                    <th className={'p-perm p-head'}>modifier le service d'un membre</th>
                                    <th className={'p-perm p-head'}>modifier le temps de service d'un membre</th>
                                    <th className={'p-perm p-head'}>voir la liste du personnel</th>
                                    <th className={'p-perm p-head'}>mettre la capacité de pilote</th>
                                    <th className={'p-perm p-head'}>modifier les grade</th>
                                    <th className={'p-perm p-head'}>gérer les permissions</th>
                                    <th className={'p-perm p-head'}>acceder à la page gestion de contenu</th>
                                    <th className={'p-perm p-head'}>poster des annonces</th>
                                    <th className={'p-perm p-head'}>acceder au logs</th>
                                    <th className={'p-perm p-head'}>valider les formations</th>
                                    <th className={'p-perm p-head'}>créé des formation</th>
                                    <th className={'p-perm p-head'}>rendre public les formations</th>
                                    <th className={'p-perm p-head'}>supprimer des formation</th>
                                    <th className={'p-perm p-head'}>acceder au statisques admins</th>
                                </tr>
                            </thead>
                            <tbody>
                                {this.state.obj.map((ob)=>
                                <tr>
                                    <td className={'grade'}>Senior Doctor</td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_A'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_A'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_B'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_B'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_C'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_C'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_D'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_D'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_E'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_E'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_F'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_F'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_G'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_G'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_H'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_H'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_I'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_I'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_J'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_J'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_K'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_K'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_L'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_L'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_M'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_M'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_N'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_N'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_O'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_O'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_P'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_P'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_Q'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_Q'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_R'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_R'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_S'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_S'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_T'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_T'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_U'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_U'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_V'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_V'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_W'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_W'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_X'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_X'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_Y'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_Y'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" id={'myonoffswitch_Z'+ob.id} tabIndex="0"/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_Z'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        )
    }
}

export default Permissions;
