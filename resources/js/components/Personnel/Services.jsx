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
    let data = null;
    if(props.data !== null){
        data = {
            labels: props.data[0] ,
            datasets: [
                {
                    label: 'Temps de service (h)',
                    data: props.data[1],
                    fill: false,
                    color: '#fff',
                    backgroundColor: ' #00FFFF',
                    borderColor: ' #00FFFF',
                },
            ],
        };
    }else{
        data = null
    }

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
            {props.data != null &&
                <Line data={data} options={options}/>
            }

        </div>
    )
}

const Servicegraph = (props) => {

    let nddata = null;
    if(props.data !== null){
        nddata = {
            labels: props.data[0] ,
            datasets: [
                {
                    label: 'durée du service (h)',
                    data: props.data[1],
                    fill: false,
                    backgroundColor: ' #00FFFF',
                    borderColor: ' #00FFFF',
                    color: '#0c2646'
                },
            ],
        };
    }else{
        nddata = null
    }

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
            {props.data != null &&
                <Line data={nddata} options={ndoptions} />
            }

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
            weekgraph: null,
            servicegraph: null,
            com: '',
            action: 0,
            time: '',
            filter: false,
            servicepop: false,
            reqs: null,
            materiallist: null,
        }
        this.getUserReq = this.getUserReq.bind(this);
    }

    async componentDidMount() {
        var req = await axios({
            url: '/data/service/user',
            method: 'GET'
        })
        this.setState({
            week: req.data.week,
            services: req.data.services,
            weekgraph: req.data.weekgraph,
            servicegraph: req.data.servicegraph,
            data:true
        })
        this.getUserReq()
        this.getmaterial()
    }

    async getUserReq() {
        await axios({
            method: 'get',
            url: '/data/service/req/mylist'
        }).then(response => {
            this.setState({reqs: response.data.reqs.reverse()})
        })

    }

    async getmaterial(){
        await axios({
            method: 'get',
            url: '/data/usersheet/null/material'
        }).then(response => {
            var material = Object.entries(response.data.material)

            this.setState({materiallist: material, materialform: response.data.material})
        })
    }


    render() {
        return (
            <div className={'Services'}>
                <div className={'scroll'}>
                    <div className={'page'} style={{filter: this.state.filter? 'blur(5px)' : 'none'}}>
                        <section className={'header'}>
                            <PagesTitle title={'Services'}/>
                            <button className={'btn'} onClick={ () => {
                                this.setState({servicepop: true, filter: true})
                            }}>Temps de service</button>
                            <button className={'btn'}>Demande de prime</button>
                        </section>
                        <section className={'graph'}>
                            <Weeklygraph data={this.state.weekgraph}/>
                            <Servicegraph data={this.state.servicegraph}/>
                        </section>
                        <section className={'infos'}>
                            <div className={'material'}>
                                <h1>Mon matériel</h1>
                                <div className={'material-list'}>
                                    {this.state.materiallist !==  null &&
                                    this.state.materiallist.map((material) =>
                                        material[1] === true &&
                                        <div className={'material-item'} key={material[0]}>
                                            {material[0] === "flare" &&
                                            <img alt={''} src={rootUrl + 'assets/images/material/flare.png'}/>
                                            }
                                            {material[0] === "lampe" &&
                                            <img alt={''} src={rootUrl + 'assets/images/material/flashlights.png'}/>
                                            }
                                            {material[0] === "flareGun" &&
                                            <img alt={''} src={rootUrl + 'assets/images/material/flaregun.png'}/>
                                            }
                                            {material[0] === "extincteur" &&
                                            <img alt={''} src={rootUrl + 'assets/images/material/fire-extinguisher.png'}/>
                                            }
                                            {material[0] === "kevlar" &&
                                            <img alt={''} src={rootUrl + 'assets/images/material/kevlar.png'}/>
                                            }
                                            <h4>{material[0]}</h4>
                                        </div>
                                    )
                                    }
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
                                        </tr>
                                        </thead>
                                        <tbody>
                                        {this.state.reqs &&
                                        this.state.reqs.map((req) =>
                                            <tr key={req.id}>
                                                <td className={'week'}>{req.week_number}</td>
                                                <td className={'modif'}>{req.adder ? '+':'-'}{req.time_quantity}</td>
                                                <td className={'com'}>{req.reason}</td>
                                                <td className={'state'}>{req.acceped === null ? 'en attente' : req.acceped ? 'acceptée' : 'refusée' }</td>
                                            </tr>
                                        )}
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
                {this.state.servicepop &&
                    <div className="modpopup" >
                    <div className={'center'}>
                        <form onSubmit={async e => {
                            e.preventDefault();
                            await axios({
                                method: 'POST',
                                url: '/data/service/req/post',
                                data: {
                                    action: this.state.action,
                                    com: this.state.com,
                                    temps: this.state.time,
                                }
                            }).then(response => {
                                if (response.status === 201) {
                                    this.getUserReq();
                                    this.setState({
                                        action: 0,
                                        com: '',
                                        time: '',
                                        filter: false,
                                        servicepop: false
                                    })
                                }
                            })
                        }}>
                            <h2>Demande de modification de temps de service</h2>
                            <div className="rowed">
                                <label>Commentaire</label>
                                <input maxLength={25} type={'text'} list={'autocomplete'} value={this.state.com} max={100} onChange={(e)=>{
                                    this.setState({com:e.target.value})
                                }}/>
                            </div>
                            <div className="rowed">
                                <label>action</label>
                                <select defaultValue={this.state.action} onChange={(e)=>this.setState({action:e.target.value})}>
                                    <option value={0} disabled>choisir</option>
                                    <option value={1}>ajouter</option>
                                    <option value={2}>enlever</option>
                                </select>
                            </div>
                            <div className="rowed">
                                <label>temps</label>
                                <input type={'time'} placeholder={'hh:mm'} value={this.state.time} onChange={(e)=>this.setState({time:e.target.value})}/>
                            </div>
                            <div className={'button'}>
                                <button onClick={()=>this.setState({servicepop: false, filter: false})} className={'btn'}>fermer</button>
                                <button type={'submit'} className={'btn'}>valider</button>
                            </div>
                        </form>
                    </div>
                </div>
                }

                <div className={'primes'}>

                </div>
            </div>
        )
    }
}
export default Services;


