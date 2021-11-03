import React from 'react';
import axios from "axios";
import dateFormat from "dateformat";
import PagesTitle from "../props/utils/PagesTitle";

class Services extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            week: null,
            services: null,
            data:false,
        }
    }

    async componentDidMount() {
        var req = await axios({
            url: '/data/service/user',
            method: 'GET'
        })
        this.setState({
            week: req.data.week,
            services: req.data.services,
            data:true
        })
    }

    render() {
        return (
            <div className={'Services'}>
                <PagesTitle title={'Services'}/>
                <button className={'btn'}>Modification de temps de service</button>
                <button className={'btn'}>Demande de prime</button>
                <section className={'week'}>
                    {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                    }
                    {this.state.data &&
                        <table >
                            <thead className={'header'}>
                            <tr>
                                <td className={'head'}>date</td>
                                <td className={'head'}>début</td>
                                <td className={'head'}>fin</td>
                                <td className={'head'}>temps en service</td>
                            </tr>
                            </thead>
                            <tbody className={'body'}>
                            {this.state.services &&
                            this.state.services.map((service) =>
                                <tr key={service.id}>
                                    <td>{dateFormat(service.created_at, 'dd/mm/yyyy')}</td>
                                    <td>{service.started_at.split(' ')[1].split(':')[0] + ':' + (service.started_at.split(' ')[1].split(':')[1].length <2 ? '0': '') + service.started_at.split(' ')[1].split(':')[1]}</td>
                                    {service.ended_at ? <td>{service.ended_at.split(':')[0] + ':' + (service.ended_at.split(':')[1].length <2 ? '0': '') + service.ended_at.split(':')[1]}</td> : <td>En service</td>}
                                    {service.total ? <td>{service.total.split(':')[0] + ':'+ (service.total.split(':')[1].length <2 ? '0': '') + service.total.split(':')[1]}</td>: <td>En service</td>}
                                </tr>
                            )
                            }
                            </tbody>
                        </table>
                        }

                </section>
                <section className={'week-list'}>
                    {!this.state.data &&
                    <div className={'load'}>
                        <img src={'/assets/images/loading.svg'} alt={''}/>
                    </div>
                    }
                    {this.state.data &&
                    <table>
                        <thead className={'header'}>
                        <tr>
                            <td>semaine</td>
                            <td>dimanche</td>
                            <td>lundi</td>
                            <td>mardi</td>
                            <td>mercredi</td>
                            <td>jeudi</td>
                            <td>vendredi</td>
                            <td>samedi</td>
                            <td>Total</td>
                        </tr>
                        </thead>
                        <tbody className={'body'}>
                        {this.state.week &&
                        this.state.week.map((oneweek)=>
                            <tr key={oneweek.id}>
                                <td>{oneweek.week_number} | {dateFormat(oneweek.created_at, 'yyyy')}</td>
                                <td>{oneweek.dimanche.split(':')[0] + ':' + oneweek.dimanche.split(':')[1]}</td>
                                <td>{oneweek.lundi.split(':')[0] + ':' + oneweek.lundi.split(':')[1]}</td>
                                <td>{oneweek.mardi.split(':')[0] + ':' + oneweek.mardi.split(':')[1]}</td>
                                <td>{oneweek.mercredi.split(':')[0] + ':' + oneweek.mercredi.split(':')[1]}</td>
                                <td>{oneweek.jeudi.split(':')[0] + ':' + oneweek.jeudi.split(':')[1]}</td>
                                <td>{oneweek.vendredi.split(':')[0] + ':' + oneweek.vendredi.split(':')[1]}</td>
                                <td>{oneweek.samedi.split(':')[0] + ':' + oneweek.samedi.split(':')[1]}</td>
                                <td>{oneweek.total.split(':')[0] + ':' + oneweek.total.split(':')[1]}</td>
                            </tr>
                        )
                        }


                        </tbody>
                    </table>

                    }

                </section>


            </div>
        )
    }
}

export default Services;
