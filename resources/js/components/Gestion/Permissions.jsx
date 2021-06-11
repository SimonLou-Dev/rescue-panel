import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import {Link} from "react-router-dom";
import TableBottom from "../props/utils/TableBottom";




class Permissions extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            obj: [],
            data: false,
        }
    }

    async componentDidMount() {
        var req = await axios({
            url: '/data/admin/grades/get',
            method: 'GET'
        })
        if(req.status === 200){
            this.setState({obj: req.data.grades, data:true})
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
                        {!this.state.data &&
                            <div className={'load'}>
                                <img src={'/assets/images/loading.svg'} alt={''}/>
                            </div>
                        }
                        {this.state.data &&
                            <table>
                            <thead>
                            <tr>
                                <th className={'p-grade p-head'}>grade</th>
                                <th className={'p-perm p-head'}>acceder au site</th>
                                <th className={'p-perm p-head'}>faire des rapports hors service</th>
                                <th className={'p-perm p-head'}>acceder au dossier hors service</th>
                                <th className={'p-perm p-head'}>voir les Bc hors service</th>
                                <th className={'p-perm p-head'}>sortir les pdf (factures / BC)</th>
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
                                <th className={'p-perm p-head'}>poster des annonces</th>
                                <th className={'p-perm p-head'}>acceder au logs</th>
                                <th className={'p-perm p-head'}>valider les formations</th>
                                <th className={'p-perm p-head'}>créer des formation</th>
                                <th className={'p-perm p-head'}>rendre public les formations</th>
                                <th className={'p-perm p-head'}>supprimer des formation</th>
                                <th className={'p-perm p-head'}>acceder au statisques admins</th>
                                <th className={'p-perm p-head'}>Acceder au facture hors service</th>
                                <th className={'p-perm p-head'}>acceder à la page gestion de contenu</th>
                            </tr>
                            </thead>
                            <tbody>
                            {this.state.obj.map((ob)=>
                                <tr>
                                    <td className={'grade'}>{ob.name}</td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_0} id={'myonoffswitch_A'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_0/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_A'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_1} id={'myonoffswitch_B'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_1/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_B'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_2} id={'myonoffswitch_C'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_2/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_C'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_3} id={'myonoffswitch_D'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_3/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_D'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_4} id={'myonoffswitch_E'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_4/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_E'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_5} id={'myonoffswitch_F'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_5/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_F'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_6} id={'myonoffswitch_G'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_6/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_G'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_7} id={'myonoffswitch_H'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_7/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_H'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_8} id={'myonoffswitch_I'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_8/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_I'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_9} id={'myonoffswitch_J'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_9/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_J'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_10} id={'myonoffswitch_K'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_10/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_K'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_11} id={'myonoffswitch_L'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_11/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_L'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_12} id={'myonoffswitch_M'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_12/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_M'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_13} id={'myonoffswitch_N'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_13/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_N'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_14} id={'myonoffswitch_O'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_14/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_O'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_15} id={'myonoffswitch_P'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_15/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_P'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_16} id={'myonoffswitch_Q'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_16/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_Q'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_17} id={'myonoffswitch_R'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_17/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_R'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_18} id={'myonoffswitch_S'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_18/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_S'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_19} id={'myonoffswitch_T'+ob.id} tabIndex="0"  onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_19/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_T'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_20} id={'myonoffswitch_U'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_20/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_U'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_21} id={'myonoffswitch_V'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_21/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_V'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_22} id={'myonoffswitch_W'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_22/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_W'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_23} id={'myonoffswitch_X'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_23/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_X'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_24} id={'myonoffswitch_Y'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_24/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_Y'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_25} id={'myonoffswitch_Z'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_25/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_Z'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <div className="onoffswitch">
                                            <input type="checkbox" className="onoffswitch-checkbox" checked={ob.perm_26} id={'myonoffswitch_ZA'+ob.id} tabIndex="0" onClick={async () => {
                                                var req = await axios({
                                                    url: '/data/admin/grades/perm_26/' + ob.id,
                                                    method: 'PUT',
                                                })
                                                if (req.status === 201) {
                                                    this.componentDidMount()
                                                }
                                            }}/>
                                            <label className="onoffswitch-label" htmlFor={'myonoffswitch_ZA'+ob.id}>
                                                <span className="onoffswitch-inner"/>
                                                <span className="onoffswitch-switch"/>
                                            </label>
                                        </div>
                                    </td>
                                </tr>)}
                            </tbody>
                        </table>
                        }

                    </div>
                </div>
            </div>
        )
    }
}

export default Permissions;
