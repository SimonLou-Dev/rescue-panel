import React from 'react';
import axios from "axios";
import dateFormat from "dateformat";
import PagesTitle from "../props/utils/PagesTitle";
import {Line, defaults, Chart} from 'react-chartjs-2';
import {rootUrl} from "../Gestion/FichePersonnel";

Chart.defaults.plugins.tooltip.enabled = true;
Chart.defaults.backgroundColor = '#6574cd';
Chart.defaults.color = '#00FFFF';
Chart.defaults.font.size = 15;
Chart.defaults.borderColor = '#6574cd';
Chart.defaults.elements.point.pointStyle = 'rect';
Chart.defaults.elements.point.radius = 5;
Chart.defaults.elements.line.tension = 0.5;

const Weeklygraph = (props) => {
    const data = {
        labels: ['30', '31', '32', '33', '34','35'],
        datasets: [
            {
                label: 'Temps de service (h)',
                data: [35, 40, 12, 20, 10, 31],
                fill: false,
                color: '#fff',
                backgroundColor: ' #00FFFF',
                borderColor: ' #00FFFF',
            },
        ],
    };


    const options = {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                x:{
                    grid:{
                        drawOnChartArea: false,

                    }
                },
                y: {
                    beginAtZero: false
                }
            },
    };


    return (
        <div className={'weeklygraph'}>
            <h1>Temps de service par semaine</h1>
            <Line data={data} options={options}/>
        </div>
    )
}

const Servicegraph = (props) => {
    const nddata = {
        labels: ['1', '2', '3', '4', '5', '6'],
        datasets: [
            {
                label: 'durée du service (h)',
                data: [3, 2, 5, 9, 8, 10],
                fill: false,
                backgroundColor: ' #00FFFF',
                borderColor: ' #00FFFF',
                color: '#0c2646'
            },
        ],
    };

    const ndoptions = {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            x: {
                grid: {
                    drawOnChartArea: false,

                }
            },
            y: {
                beginAtZero: false
            }
        }
    };

    return (
        <div className={'ServiceGraph'}>
            <h1>durées des dernier service</h1>
            <Line data={nddata} options={ndoptions} />
        </div>
    )
}

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
                <div className={'scroll'}>
                    <div className={'page'} style={{filter: this.state.blur? 'blur(5px)' : 'none'}}>
                        <section className={'header'}>
                            <PagesTitle title={'Services'}/>
                            <button className={'btn'}>Temps de service</button>
                            <button className={'btn'}>Demande de prime</button>
                        </section>
                        <section className={'graph'}>
                            <Weeklygraph/>
                            <Servicegraph/>
                        </section>
                        <section className={'infos'}>
                            <div className={'material'}>
                                <h1>Mon matériel</h1>
                                <div className={'material-list'}>
                                    <div className={'material-item'}>
                                        <img alt={''} src={rootUrl + 'assets/images/material/flare.png'}/>
                                        <h4>flare</h4>
                                    </div>
                                    <div className={'material-item'}>
                                        <img alt={''} src={rootUrl + 'assets/images/material/flashlights.png'}/>
                                        <h4>lampe</h4>
                                    </div>
                                    <div className={'material-item'}>
                                        <img alt={''} src={rootUrl + 'assets/images/material/flaregun.png'}/>
                                        <h4>flare gun</h4>
                                    </div>
                                    <div className={'material-item'}>
                                        <img alt={''} src={rootUrl + 'assets/images/material/fire-extinguisher.png'}/>
                                        <h4>extinguisher</h4>
                                    </div>
                                    <div className={'material-item'}>
                                        <img alt={''} src={rootUrl + 'assets/images/material/kevlar.png'}/>
                                        <h4>kevlar</h4>
                                    </div>
                                </div>
                            </div>
                            <div className={'ServiceReq'}>
                                <h1>Demande de modification de temps de service</h1>
                                <div className={'table'}>
                                    <table>
                                        <thead>
                                        <tr>
                                            <th className={'week'}>semaine</th>
                                            <th className={'modif'}>modification</th>
                                            <th className={'com'}>commentaire</th>
                                            <th className={'state'}>état</th>
                                            <th className={'raison'}>raison</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'modif'}>+12:05</td>
                                            <td className={'com'}>eu, index! Clemens,paaaaa</td>
                                            <td className={'state'}>en attente</td>
                                            <td className={'raison'}/>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'modif'}>+12:05</td>
                                            <td className={'com'}/>
                                            <td className={'state'}>refusé</td>
                                            <td className={'raison'}>trop de temps</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'modif'}>+12:05</td>
                                            <td className={'com'}/>
                                            <td className={'state'}>refusé</td>
                                            <td className={'raison'}>trop de temps</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'modif'}>+12:05</td>
                                            <td className={'com'}/>
                                            <td className={'state'}>refusé</td>
                                            <td className={'raison'}>trop de temps</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'modif'}>+12:05</td>
                                            <td className={'com'}/>
                                            <td className={'state'}>refusé</td>
                                            <td className={'raison'}>trop de temps</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'modif'}>+12:05</td>
                                            <td className={'com'}/>
                                            <td className={'state'}>refusé</td>
                                            <td className={'raison'}>trop de temps</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'modif'}>+00:30</td>
                                            <td className={'com'}>Oublie MDT</td>
                                            <td className={'state'}>accepté</td>
                                            <td className={'raison'}/>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div className={'Primes'}>
                                <h1>Demande de prime</h1>
                                <div className={'table'}>
                                    <table>
                                        <thead>
                                        <tr>
                                            <th className={'week'}>semaine</th>
                                            <th className={'price'}>prix</th>
                                            <th className={'raison'}>raison</th>
                                            <th className={'state'}>état</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un event</td>
                                            <td className={'state'}>refusée</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un event</td>
                                            <td className={'state'}>en attente</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un BC</td>
                                            <td className={'state'}>acceptée</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un event</td>
                                            <td className={'state'}>refusée</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un event</td>
                                            <td className={'state'}>en attente</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un BC</td>
                                            <td className={'state'}>acceptée</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un event</td>
                                            <td className={'state'}>refusée</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un event</td>
                                            <td className={'state'}>en attente</td>
                                        </tr>
                                        <tr>
                                            <td className={'week'}>42</td>
                                            <td className={'price'}>$800</td>
                                            <td className={'raison'}>participation à un BC</td>
                                            <td className={'state'}>acceptée</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
                <div className={'servicemodpopup'}>

                </div>
                <div className={'primes'}>

                </div>
            </div>
        )
    }
}
export default Services;


