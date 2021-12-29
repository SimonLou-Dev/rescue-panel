import React from 'react';
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";
import {Link} from "react-router-dom";


class PrimesRequestAdmin extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            list: null,
        }
    }

    async componentDidMount() {
        await axios({
            method: 'GET',
            url: '/data/primes/getall',
        }).then(response => {
            this.setState({list: response.data.primes})
        })
    }

    render() {
        return (
            <div className={"adminservicereq"}>
                <section className={'header'}>
                    <PagesTitle title={'Gestion des primes'}/>
                    <Link to={'/gestion/rapport'} className={'btn'}>Retour</Link>
                </section>
                <section className={'content'}>
                    <div className={'table-container'}>
                        <table>
                            <thead>
                            <tr>
                                <th>personnel</th>
                                <th>date</th>
                                <th>semaine</th>
                                <th>montant</th>
                                <th>raison</th>
                                <th>état</th>
                                <th>action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {this.state.list && this.state.list.map((req) =>
                                <tr key={req.id}>
                                    <td>{req.get_user.name}</td>
                                    <td>{req.week_number}</td>
                                    <td>{req.date}</td>
                                    <td>${req.get_item.montant}</td>
                                    <td>{req.get_item.name}</td>
                                    <td>{req.accepted === null ? 'en attente' : (req.accepted === 1 ? 'acceptée' : 'refusée') }</td>
                                    <td>{req.accepted === null &&
                                    <button className={'btn'} onClick={async () => {
                                        await axios({
                                            method: 'PUT',
                                            url: '/data/primes/accept/' + req.id
                                        }).then(() => this.componentDidMount())
                                    }}>accepter</button>
                                    }
                                        {req.accepted === null &&
                                        <button className={'btn'} onClick={async () => {
                                            await axios({
                                                method: 'PUT',
                                                url: '/data/primes/refuse/' + req.id
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

export default PrimesRequestAdmin;
