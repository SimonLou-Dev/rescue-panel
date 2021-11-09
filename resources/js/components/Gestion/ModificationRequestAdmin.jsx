import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import {Link} from "react-router-dom";


class ModificationRequestAdmin extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            list: null,
        }
    }

    async componentDidMount() {
        await axios({
            method: 'GET',
            url: '/data/service/req/waitinglist',
        }).then(response => {
            this.setState({list: response.data.list})
        })
    }

    render() {
        return (
            <div className={"adminservicereq"}>
                <section className={'header'}>
                    <PagesTitle title={'Modification des temps de service'}/>
                    <Link to={'/gestion/rapport'} className={'btn'}>Retour</Link>
                </section>
                <section className={'content'}>
                   <div className={'table-container'}>
                       <table>
                           <thead>
                           <tr>
                               <th>personnel</th>
                               <th>semaine</th>
                               <th>temps</th>
                               <th>raison</th>
                               <th>état</th>
                               <th>responsable</th>
                               <th>action</th>
                           </tr>
                           </thead>
                           <tbody>
                           {this.state.list && this.state.list.map((req) =>
                               <tr key={req.id}>
                                   <td>{req.get_user.name}</td>
                                   <td>{req.week_number}</td>
                                   <td>{req.adder ? '+':'-'}{req.time_quantity}</td>
                                   <td>{req.reason}</td>
                                   <td>{req.acceped === null ? 'en attente' : req.acceped ? 'acceptée' : 'refusée' }</td>
                                   <td>{req.admin_id ?req.get_admin.name  : ''} </td>
                                   <td>{!req.admin_id &&
                                    <button className={'btn'} onClick={async () => {
                                        await axios({
                                            method: 'PUT',
                                            url: '/data/service/req/accept/' + req.id
                                        }).then(() => this.componentDidMount())
                                    }}>accepter</button>
                                   }
                                   {!req.admin_id &&
                                       <button className={'btn'} onClick={async () => {
                                           await axios({
                                               method: 'PUT',
                                               url: '/data/service/req/refuse/' + req.id
                                           }).then(() => this.componentDidMount())
                                       }}>refuser</button>
                                   }
                                   </td>

                               </tr>
                           )
                           }
                           </tbody>
                       </table>
                   </div>
                </section>

            </div>
        )
    }
}

export default ModificationRequestAdmin;
