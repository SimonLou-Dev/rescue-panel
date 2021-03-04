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
                            {
                                console.log(this.state.services)
                            }
                            {this.state.services &&
                            this.state.services.map((service) =>
                                <tr key={service.id}>
                                    <td>{dateFormat(service.created_at, 'dd/mm/yyyy')}</td>
                                    <td>{service.Started_at.substring(11,16)}</td>
                                    {service.EndedAt ? <td>{service.EndedAt.substring(-1,5)}</td> : <td>En service</td>}
                                    {service.Total ? <td>{service.Total.substring(-1,5)}</td>: <td>En service</td>}
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
                            <td>lundi</td>
                            <td>mardi</td>
                            <td>mercredi</td>
                            <td>jeudi</td>
                            <td>vendredi</td>
                            <td>samedi</td>
                            <td>dimanche</td>
                            <td>Total</td>
                        </tr>
                        </thead>
                        <tbody className={'body'}>
                        {this.state.week &&
                        this.state.week.map((oneweek)=>
                            <tr key={oneweek.id}>
                                <td>{oneweek.week} | {dateFormat(oneweek.created_at, 'yyyy')}</td>
                                <td>{oneweek.lundi.substring(-1,5)}</td>
                                <td>{oneweek.mardi.substring(-1,5)}</td>
                                <td>{oneweek.mercredi.substring(-1,5)}</td>
                                <td>{oneweek.jeudi.substring(-1,5)}</td>
                                <td>{oneweek.vendredi.substring(-1,5)}</td>
                                <td>{oneweek.samedi.substring(-1,5)}</td>
                                <td>{oneweek.dimanche.substring(-1,5)}</td>
                                <td>{oneweek.total.substring(-1,5)}</td>
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
