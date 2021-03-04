import React from 'react';
import axios from "axios";
import dateFormat from "dateformat";
import PagesTitle from "../props/utils/PagesTitle";

class Logs extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: false,
            range: 25,
            page: 1,
            type: 1,
            nbrpages: "?",
            datas: null,
            nbrlignes: "?",
        }
        this.request = this.request.bind(this);
        this.suspense = this.suspense.bind(this);
    }

    componentDidMount() {
        this.request(this.state.range, this.state.page, this.state.type);
    }

    async request(range, page, type) {
        this.suspense(true);
        var req = await axios({
            url : '/data/logs/' + range + '/'+ page +'/'+ type,
            method: 'GET',
        })
        this.setState({
            datas : req.data.datas,
            data:true,
            nbrpages:req.data.pages,
            nbrlignes: req.data.lignes,
        })
    }

    suspense(bool){
        this.setState({data:!bool});
    }

    render() {
        return (
            <div className={'Logs'}>
                <section className={'header'}>
                    <PagesTitle title={'Logs'}/>
                    <div className={'logs-select'}>
                            <select value={this.state.type} onChange={(e)=>{
                                this.setState({type: e.target.value});
                                this.request(this.state.range, 1, e.target.value);
                            }}>
                                <option value={1}>Rapports</option>
                                <option value={2}>Services</option>
                                <option value={3}>Factures</option>
                                <option value={4}>black codes</option>
                            </select>

                    </div>
                </section>
                <section className={'log-list'}>
                    <div className={'logs-header'}>
                        <div className={'left'}>
                            <p>Il y a {this.state.nbrlignes} lignes</p>
                        </div>
                        <div className={'pages'}>
                            <label>Résultats par pages : </label>
                            <select value={this.state.range} onChange={(e)=>{
                                this.setState({range: e.target.value});
                                this.request(e.target.value, this.state.page, this.state.type);
                            }}>
                                <option value={25}>25</option>
                                <option value={50}>50</option>
                                <option value={60}>100</option>
                            </select>
                            <button onClick={(e)=>{
                                if(this.state.page !== 1){
                                    this.setState({page: this.state.page-1});
                                    this.request(this.state.range, this.state.page-1, this.state.type);
                                }
                            }}>&lt;</button>
                            <p>{this.state.page}/{this.state.nbrpages}</p>
                            <button onClick={(e)=>{
                                if(this.state.page !== this.state.nbrpages){
                                    this.setState({page: this.state.page+1});
                                    this.request(this.state.range, this.state.page+1, this.state.type);
                                }
                            }}>&gt;</button>
                        </div>
                    </div>
                    {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                    }
                    {this.state.data &&
                        <div className={'table'}>
                            {this.state.type == 1 &&
                                <table>
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>patient</th>
                                            <th>prix</th>
                                            <th>payé</th>
                                            <th>ajouté le</th>
                                            <th>modifié le</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    {
                                        this.state.datas.map((data)=>
                                            <tr>
                                                <td>{data.id}</td>
                                                <td>{data.patient.vorname} {data.patient.name}</td>
                                                <td>{data.prix}</td>
                                                <td>{data.facture?(data.facture.payed? 'oui':'non'):'pas de facture'}</td>
                                                <td>{dateFormat(data.created_at, 'dd/mm/yyyy à H:MM')}</td>
                                                <td>{dateFormat(data.updated_at, 'dd/mm/yyyy à H:MM')}</td>
                                            </tr>
                                        )
                                    }
                                    </tbody>
                            </table>
                            }
                            {this.state.type == 2 &&
                            <table>
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>personnel</th>
                                    <th>debut</th>
                                    <th>fin</th>
                                    <th>total</th>
                                </tr>
                                </thead>
                                <tbody>
                                {
                                    this.state.datas.map((data)=>
                                        <tr>
                                            <td>{data.id}</td>
                                            <td>{data.get_user.name}</td>
                                            <td>{dateFormat(data.created_at, 'dd/mm/yyyy à H:MM')}</td>
                                            <td>{data.EndedAt?data.EndedAt: 'en service'}</td>
                                            <td>{data.Total?data.Total: 'en service'}</td>

                                        </tr>
                                    )
                                }
                                </tbody>
                            </table>
                            }
                            {this.state.type == 3 &&
                            <table>
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>patient</th>
                                    <th>id rapport</th>
                                    <th>payed</th>
                                    <th>prix</th>
                                    <th>modifié le</th>
                                    <th>créé le</th>
                                </tr>
                                </thead>
                                <tbody>
                                {
                                    this.state.datas.map((data)=>
                                        <tr>
                                            <td>{data.id}</td>
                                            <td>{data.patient.vorname} {data.patient.name}</td>
                                            <td>{data.rapport_id?data.rapport_id : 'pas de rapport'}</td>
                                            <td>{data.payed? 'oui':'non'}</td>
                                            <td>{data.price}</td>
                                            <td>{dateFormat(data.updated_at, 'dd/mm/yyyy à H:MM')}</td>
                                            <td>{dateFormat(data.created_at, 'dd/mm/yyyy à H:MM')}</td>
                                        </tr>
                                    )
                                }
                                </tbody>
                            </table>
                            }
                            {this.state.type == 4 &&
                            <table>
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>créateur</th>
                                    <th>terminé</th>
                                    <th>créé le</th>
                                    <th>modifier le</th>
                                </tr>
                                </thead>
                                <tbody>
                                {
                                    this.state.datas.map((data)=>
                                        <tr>
                                            <td>{data.id}</td>
                                            <td>{data.user.name}</td>
                                            <td>{data.ended?'oui': 'non'}</td>
                                            <td>{dateFormat(data.created_at, 'dd/mm/yyyy à H:MM')}</td>
                                            <td>{dateFormat(data.updated_at, 'dd/mm/yyyy à H:MM')}</td>
                                        </tr>
                                    )
                                }
                                </tbody>
                            </table>
                            }
                        </div>
                    }


                </section>
            </div>
        )
    }
}

export default Logs;
